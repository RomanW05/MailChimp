import requests
import json
import hashlib

# Credentials
api_token = 'API_TOKEN'
api_public_token = 'API_TOKEN_PUBLIC'
url = 'https://api.goaffpro.com/v1/admin/affiliates'
database_username = 'USERNAME_DATABASE'
database_password = 'USERNAME_PASSWORD'
database_name = 'DATABASE_NAME'
database_host = 'DATABASE_HOST'

# Retrieve subscribers list
headers = {'x-goaffpro-access-token': api_token}
data = {"fields": [id]}
params = (
    ("fields", ["id, name, email, coupon"]),
)
r = requests.get(url, params=params, headers=headers)
response = r.text
subscribers_list = json.loads(response)

# Add all subscribers from last to first until
# one of them had already been added, wich means all were added
m = hashlib.md5()  # md5 hashing to display link in the newsletter
for elem in range(len( subscribers_list["affiliates"]), -1, -1, -1):
    m.update(subscribers_list["affiliates"][elem]["email"].encode('utf8'))
    hashed = m.hexdigest()
    userdata = {}
    userdata['pass'] = 'CUSTOM_PASSWORD_PHP'
    userdata['name'] = subscribers_list["affiliates"][elem]["name"]
    userdata['email'] = subscribers_list["affiliates"][elem]["email"]
    userdata['subscribed'] = 1
    userdata['hashed'] = hashed
    userdata['db_username'] = database_username
    userdata['db_password'] = database_password
    userdata['db_databe_name'] = database_name
    userdata['db_host'] = database_host
    url = 'example.com/hidden_files/update_db/update_db.php'
    resp = requests.post(url, params=userdata)
    print(resp.text)
    if 'already added' in resp.text:
        break
