# -*- coding: utf-8 -*-
"""
Created on Fri Oct  6 17:36:30 2023

@author: Ivan
"""

import json
import xml.etree.ElementTree as ET

# Load the JSON data from a file
with open('email.json', 'r') as json_file:
    data = json.load(json_file)

# Create an XML element for the root element
root = ET.Element('exercise3')

# Loop through each email object in the JSON data
for email in data['email']:
    email_elem = ET.SubElement(root, 'email')

    sender_elem = ET.SubElement(email_elem, 'sender')
    sender_elem.text = email['sender']

    title_elem = ET.SubElement(email_elem, 'title')
    title_elem.text = email['title']

    date_elem = ET.SubElement(email_elem, 'date')
    date_elem.text = email['date']

    content_elem = ET.SubElement(email_elem, 'content')
    content_elem.text = email['content']

    attachments_elem = ET.SubElement(email_elem, 'attachments')
    files = email['attachments']['file']
    for attachment in files:
        file_elem = ET.SubElement(attachments_elem, 'file')
        file_elem.text = attachment

    receivers_elem = ET.SubElement(email_elem, 'receivers')
    to_receivers = email['receivers']['to']
    cc_receivers = email['receivers']['cc']
    cs_receivers = email['receivers']['cs']
    
    for to in to_receivers:
        to_elem = ET.SubElement(receivers_elem, 'to')
        to_elem.text = to
    for cc in cc_receivers:
        cc_elem = ET.SubElement(receivers_elem, 'cc')
        cc_elem.text = cc
    for cs in cs_receivers:
        cs_elem = ET.SubElement(receivers_elem, 'cs')
        cs_elem.text = cs

# Create an ElementTree and write it to an XML file
tree = ET.ElementTree(root)
tree.write('emailfinal.xml', encoding='utf-8', xml_declaration=True)