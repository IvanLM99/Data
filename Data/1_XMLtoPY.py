# -*- coding: utf-8 -*-
"""
Created on Fri Oct  6 11:56:30 2023

@author: Ivan
"""

import xml.etree.ElementTree as ET

# Parse the XML file
tree = ET.parse("emailfinal.xml")
root = tree.getroot()

# Initialize an email counter
email_counter = 0

# Iterate emails
for email in root:
    email_counter += 1
    print("EMAIL NUMBER:", email_counter)
    
    # Extract email information
    sender = email.find("sender").text
    title = email.find("title").text
    date = email.find("date").text
    content = email.find("content").text      

    print("SENDER:", sender)
    print("TITLE:", title)
    print("DATE:", date)
    print("CONTENT:", content)
    
    # Extract attachments information
    files = email.find("attachments")
    for f in files:
        filename = f.text
        print("FILE:", filename)

    # Extract receiver addresses
    print("RECEIVERS:")
    receivers = email.find("receivers")
    
    to_recipients = receivers.findall("to")
    for to in to_recipients:
        to_address = to.text
        print("  To:", to_address)
    
    cc_recipients = receivers.findall("cc")
    for cc in cc_recipients:
        cc_address = cc.text
        print("  CC:", cc_address)
    
    cs_recipients = receivers.findall("cs")
    for cs in cs_recipients:
        cs_address = cs.text
        print("  CS:", cs_address)
        
    # Add a separator for better readability
    print("-" * 50)