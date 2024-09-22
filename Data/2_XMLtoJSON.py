# -*- coding: utf-8 -*-
"""
Created on Fri Oct  6 15:45:16 2023

@author: Ivan
"""

import xml.etree.ElementTree as ET
import json

# Parse the XML file
tree = ET.parse('email.xml')
root = tree.getroot()

# Initialize a list to store email objects
emails = []

# Loop through each <email> element
for email_elem in root.findall('email'):
    email = {}
    email['sender'] = email_elem.find('sender').text
    email['title'] = email_elem.find('title').text
    email['date'] = email_elem.find('date').text
    email['content'] = email_elem.find('content').text

    # Initialize lists for attachments and receivers
    email['attachments'] = {'file': []}
    email['receivers'] = {'to': [], 'cc': [], 'cs': []}

    # Process attachments
    attachments_elem = email_elem.find('attachments')
    for attachment_elem in attachments_elem.findall('file'):
        email['attachments']['file'].append(attachment_elem.text)

    # Process receivers
    receivers_elem = email_elem.find('receivers')
    for to_elem in receivers_elem.findall('to'):
        email['receivers']['to'].append(to_elem.text)
        
    for cc_elem in receivers_elem.findall('cc'):
        email['receivers']['cc'].append(cc_elem.text)
        
    for cs_elem in receivers_elem.findall('cs'):
        email['receivers']['cs'].append(cs_elem.text)

    emails.append(email)

# Create a JSON object
json_data = {'email': emails}

# Write the JSON data to a file
with open('email.json', 'w') as json_file:
    json.dump(json_data, json_file, indent=4)