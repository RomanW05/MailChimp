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


#  List subscribed ambassadors from online db. We do not want
#  to send an email to an unsubscribed user
def subscribed_ambassadors():
    userdata = {}
    userdata['pass'] = 'CUSTOM_PASSWORD_PHP'
    userdata['db_username'] = 'DATABASE_USERNAME'
    userdata['db_password'] = 'DATABASE_PASSWORD'
    userdata['db_databe_name'] = 'DATABASE_NAME'
    userdata['db_host'] = 'DATABASE_HOST'
    url = 'https://www.example.com/hidden_files/give_db_server.php'
    resp = requests.post(url, params=userdata)

    return resp


# List GoAffPro ambassadors
def goaffpro_ambassadors():
    api_token = 'API_TOKEN'
    api_public_token = 'API_PUBLIC_TOKEN'
    url = 'https://api.goaffpro.com/v1/admin/affiliates'
    headers = {'x-goaffpro-access-token': api_token}
    data = {"fields": [id]}
    params = (
            ("fields", ["email, coupon"]),
            )
    r = requests.get(url, params=params, headers=headers)
    response = r.text
    a = json.loads(response)
    goaffpro_subs = []

    for elem in range(len(a["affiliates"])):
        coupon = a["affiliates"][elem]["coupon"]
        email = a["affiliates"][elem]["email"]
        fields = [email, coupon]
        goaffpro_subs.append(fields)

    return goaffpro_subs


# Newsletter html text
def newsletter_text():
    __location__ = os.path.realpath(
            os.path.join(os.getcwd(), os.path.dirname(__file__)))
    for file in os.listdir(__location__):
        if file.endswith("_newsletter.html"):
            newsletter_path = os.path.join(__location__, file)
            newsletter_number = file.split('_')
            newsletter_number = newsletter_number[0]
    newsletter_text = ''
    with open(newsletter_path, 'r', encoding="utf8") as text:
        newsletter_text = text.read()

    return newsletter_text, newsletter_number


def newsletter_text_creator(name, hashed, coupon,
                            newsletter_text, newsletter_number
                            ):
    tracking_image = '<img src="https://www.example.com/hidden_files/newsletter_tracker.php?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '">'
    unsubscribe_link = '<a href="https://www.example.com/hidden_files/unsubscribe_user.php?hashed=' + hashed + '">unsubscribe</a>'
    action_click = '<a href="https://example.com/hidden_files/redirect_to_website.php?hashed=' + hashed + '&newsletter=' + newsletter_number + '">'
    coupon = 'https://example.com/?ref=' + coupon
    ambassador_unsubscribe_ref = "<a href='Unsubscribe_code'>"
    if name[-1] == ' ':
        name = name[:-1]
    ambassador_greeting_ref = 'Hi ' + name + ','
    image1 = '<img src="https://www.example.com/hidden_files/request_image.php/?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '_1" alt="img" loading="lazy"/>'
    image2 = '<img src="https://www.example.com/hidden_files/request_image.php/?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '_2" alt="img" loading="lazy"/>'
    image3 = '<img src="https://www.example.com/hidden_files/request_image.php/?hashed=' + hashed + '&newsletter_number=' + newsletter_number + '_3" alt="img" loading="lazy"/>'
    body = newsletter_text
    body = body.replace('Hey_XXX,', ambassador_greeting_ref)
    body = body.replace('<img src="request_image1">', image1)
    body = body.replace('<img src="request_image2">', image2)
    body = body.replace('<img src="request_image3">', image3)
    body = body.replace('ambassador_discount_code', coupon)
    body = body.replace("<a href='Unsubscribe_code'>", unsubscribe_link)
    body = body.replace('action_redirect_to_website', action_click)

    return body


# send one email newsletter
def send_email(email, message, subject):
    fromaddr = "YOUR_EMAIL@COMPANY_NAME.com"
    toaddr = email
    msg = MIMEMultipart()
    msg['From'] = fromaddr
    msg['To'] = toaddr
    msg['Subject'] = subject
    body = message
    msg.attach(MIMEText(body, 'html'))  # 'html' or 'plain'
    server = smtplib.SMTP_SSL('MAIL.SERVICE_EMAIL.com', 465)
    server.login(fromaddr, "YOUR_EMAIL_PASSWORD")
    text = msg.as_string()
    server.sendmail(fromaddr, toaddr, text)
    server.quit()


def send_all_newsletters(resp, goaffpro_subs, newsletter_text,
                         newsletter_number, subject, email_limit,
                         cooldown_time
                         ):
    online_subs = []
    emails_sent = 0

    # by splitting resp you have the list of all subscribers
    resp = resp.text.split(']')
    for x in resp:

        # clean the data
        x = x.replace('[', '')
        x = x.split(',')
        if x == [""]:
            continue

        # arange data
        subscriber_id = x[0]
        subscriber_name = x[1]
        subscriber_email = x[2]
        is_subscribed = x[3]
        subscriber_hashed = x[4]
        subscriber_coupon = x[5]
        if is_subscribed == '1':  # willing to receive newsletters
            body = newsletter_text_creator(subscriber_name, subscriber_hashed,
                                           subscriber_coupon, newsletter_text,
                                           newsletter_number
                                           )
            email = subs[0]
            send_email(email, body, subject)
            print('emails sent: ', emails_sent, '/', len(goaffpro_subs))
            emails_sent += 1
            if emails_sent >= email_limit:
                print(f'''email limit of {email_limit} reached, cooling down
                          for {cooldown_time} seconds''')
                time.sleep(cooldown_time)

    userdata = {}
    userdata['pass'] = 'CUSTOM_PASSWORD_PHP'
    userdata['db_username'] = 'DATABASE_USERNAME'
    userdata['db_password'] = 'DATABASE_PASSWORD'
    userdata['db_databe_name'] = 'DATABASE_NAME'
    userdata['db_host'] = 'DATABASE_HOST'
    userdata['ambassadors_total'] = emails_sent
    userdata['newsletter_number'] = str(newsletter_number) + '_2'
    url = 'https://www.example.com/hidden_files/emails_sent_server.php'
    resp = requests.post(url, params=userdata)
    print(resp.text)


def main():
    email_limit = 800  # Service provider max emails sent limit every 2 hours
    cooldown_time = 60 * 60 * 2  # 2 hours cooldown. Server provider limit
    resp = subscribed_ambassadors()
    goaffpro_subs = goaffpro_ambassadors()
    newsletter_text_raw, newsletter_number = newsletter_text()
    subject = "Lovely Doggos Ambassador's Tips and More!"
    send_all_newsletters(resp, goaffpro_subs,
                         newsletter_text_raw, newsletter_number, subject
                         )

if __name__ == "__main__":
    main()
