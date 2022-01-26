import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
import os
import requests
import json

'''
This script creates and sends a customized html message to each and everyone
of your subscribers list
1. subscribed_ambassadors(). Retreives all subscribers from the database
2. newsletter_text(). Loads the newsletter html content template.
3. newsletter_text_creator(). Takes that loaded html template and substitutes
    the keywords for specific ones in order to personalize the newsletter.
    Ex: Hey_XXX gets substituted by the subscribers name
4. send_email(). Sends one email to one subscriber
5. send_all_newsletters().
    1. Takes the response from subscribed_ambassadors()
    2. Splits it into
'''

def database_credentials():
    # Dictionary with credentials
    userdata = {}
    userdata['pass'] = 'YOUR_PHP_PASSWORD'
    userdata['db_username'] = 'DATABASE_USERNAME'
    userdata['db_password'] = 'DATABASE_PASSWORD'
    userdata['db_databe_name'] = 'DATABASE_NAME'
    userdata['db_host'] = 'DATABASE_HOST'

    return userdata


def connect_endpoint(endpoint, params):
    # Call the API endpoint with parameters as a dictionary
    response = requests.post(endpoint, params=params)

    return response


def subscribed_ambassadors():
    # Retrieve a list of subscribers from online db
    endpoint = 'https://www.example.com/hidden_files/give_db_server.php'
    response = connect_endpoint(endpoint, database_credentials())

    return response


def newsletter_text():
    '''
    List all files within the working directory of this script
    Select the one ending with _newsletter.html, the finished newsletter
    Copy its content and return it along side with the newsletter edition
    which is 1 or 2 or 3, ...
    '''
    # Full path or current working directory
    __location__ = os.path.realpath(
            os.path.join(os.getcwd(), os.path.dirname(__file__)))
    for file in os.listdir(__location__):  # List all files and folders
        if file.endswith("_newsletter.html"):  # Your match
            newsletter_path = os.path.join(__location__, file)
            newsletter_number = file.split('_')
            newsletter_number = newsletter_number[0]  # Newsletter number
    newsletter_text = ''  # variable to write the body of the html
    with open(newsletter_path, 'r', encoding="utf8") as text:
        newsletter_text = text.read()

    return newsletter_text, newsletter_number


def newsletter_text_creator(name, hashed, coupon,
                            newsletter_text, newsletter_number
                            ):
    '''
    Customize the newsletter to each subscriber
    Replace the keywords from the body of the newsletter.html file
    with links to endpoints and personalized data:
    '''
    # Place variables to customize the body text
    unsubscribe_link = '<a href="https://www.example.com/hidden_files/unsubscribe_user.php?hashed=' + hashed + '">unsubscribe</a>'
    redirect_link = '<a href="https://example.com/hidden_files/redirect_to_website.php?hashed=' + hashed + '&newsletter=' + newsletter_number + '">'
    coupon = 'https://example.com/?ref=' + coupon
    if name[-1] == ' ':
        name = name[:-1]
    ambassador_greeting_ref = 'Hi ' + name + ','
    image1 = '<img src="https://www.example.com/hidden_files/request_image.php/?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '_1" alt="img" loading="lazy"/>'
    image2 = '<img src="https://www.example.com/hidden_files/request_image.php/?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '_2" alt="img" loading="lazy"/>'
    image3 = '<img src="https://www.example.com/hidden_files/request_image.php/?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '_3" alt="img" loading="lazy"/>'

    # Replace body keywords with customized text
    body = newsletter_text
    body = body.replace('<keyword greeting>', ambassador_greeting_ref)
    body = body.replace('<keyword image1>', image1)
    body = body.replace('<keyword image2>', image2)
    body = body.replace('<keyword image3>', image3)
    body = body.replace('<keyword coupon>', coupon)
    body = body.replace("<keyword unsubscribe>", unsubscribe_link)
    body = body.replace('<keyword redirect>', redirect_link)

    return body


def send_email(email, message, subject):
    # Send one single email
    from_address = "YOUR_EMAIL@COMPANY_NAME.com"
    to_address = email
    msg = MIMEMultipart()  # Email with images
    msg['From'] = from_address
    msg['To'] = to_address
    msg['Subject'] = subject
    body = message
    msg.attach(MIMEText(body, 'html'))  # 'html' or 'plain'
    server = smtplib.SMTP_SSL('MAIL.SERVICE_EMAIL.com', 465)
    server.login(from_address, "YOUR_EMAIL_PASSWORD")
    text = msg.as_string()
    server.sendmail(from_address, to_address, text)
    server.quit()


def send_all_newsletters(response, newsletter_text,
                         newsletter_number, subject, email_limit,
                         cooldown_time
                         ):
    emails_sent = 0

    # By splitting the response you have a list with all the subscribers
    response = response.text.split(']')
    for x in response:

        # Clean the data
        x = x.replace('[', '')
        x = x.split(',')
        if x == [""]:
            continue

        # Rename variables
        subscriber_id = x[0]
        subscriber_name = x[1]
        subscriber_email = x[2]
        is_subscribed = x[3]
        subscriber_hashed = x[4]
        subscriber_coupon = x[5]
        if is_subscribed == '1':  # Is willing to receive newsletters
            body = newsletter_text_creator(subscriber_name, subscriber_hashed,
                                           subscriber_coupon, newsletter_text,
                                           newsletter_number
                                           )
            send_email(subscriber_email, body, subject)
            print(f'emails sent:  {emails_sent}, {len(response)}')
            emails_sent += 1
            if emails_sent >= email_limit:
                print(f'''email limit of {email_limit} reached, cooling down
                          for {cooldown_time} seconds''')
                time.sleep(cooldown_time)

    # Update database for analytics
    userdata = database_credentials()
    userdata['ambassadors_total'] = emails_sent
    userdata['newsletter_number'] = str(newsletter_number) + '_2'
    url = 'https://www.example.com/hidden_files/emails_sent_server.php'
    response = connect_endpoint(url, userdata)
    print(response.text)


def main():
    # Set variables
    email_limit = 800  # Service provider max emails sent limit every 2 hours
    cooldown_time = 60 * 60 * 2  # 2 hours cooldown. Server provider limit
    subject = "Lovely Doggos Ambassador's Tips and More!"
    response = subscribed_ambassadors()
    newsletter_text_raw, newsletter_number = newsletter_text()
    send_all_newsletters(response, newsletter_text_raw, 
                         newsletter_number, subject,
                         email_limit, cooldown_time
                         )

if __name__ == "__main__":
    main()
