# -*- coding: utf-8 -*-
"""
Created on Wed Oct 18 12:07:06 2023

@author: Ivan
"""

import xml.etree.ElementTree as ET
from collections import defaultdict

# Create a defaultdict to store name counts
name_count = defaultdict(int)
n = 0

# Iterate over the XML files
for filename in ['reduce_1.xml','reduce_2.xml','reduce_3.xml']:
    tree = ET.parse(filename)
    root = tree.getroot()

    # Iterate over the 'name' elements and increment the count
    for person in root.findall("item"):
        name = person.find('name').text
        value = int(person.find("value").text)
        name_count[name] += value


# Create the XML result document
result_root = ET.Element("reduce")

# Add name count elements to the result document
for name, count in name_count.items():
    
    # Create an item element
    item_element = ET.SubElement(result_root, "item")

    # Add the 'name' and 'value' elements to the item
    name_element = ET.SubElement(item_element, "name")
    name_element.text = name

    value_element = ET.SubElement(item_element, "value")
    value_element.text = str(count)

# Create an ElementTree object and write it to an XML file
result_tree = ET.ElementTree(result_root)
result_tree.write("Final_reduce.xml", encoding="utf-8")
