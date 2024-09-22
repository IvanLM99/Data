# -*- coding: utf-8 -*-
"""
Created on Wed Oct 18 12:04:38 2023

@author: Ivan
"""

import xml.etree.ElementTree as ET
from collections import defaultdict

# Create a defaultdict to store name counts
name_count = defaultdict(int)

# Parse the input XML file
tree = ET.parse("Map_1.xml")
root = tree.getroot()

# Iterate over the 'item' elements and 
# extract names and values
for item in root.findall("item"):
    name = item.find("name").text
    value = int(item.find("value").text)
    
    # Increment the count for each name
    name_count[name] += value

# Create a new map element for the results
result_map = ET.Element("reduce")

# Iterate over the unique names and their counts
for name, count in name_count.items():
    # Create an item element
    item_element = ET.Element("item")

    # Add the 'name' and 'value' elements to the item
    name_element = ET.Element("name")
    name_element.text = name
    item_element.append(name_element)

    value_element = ET.Element("value")
    value_element.text = str(count)
    item_element.append(value_element)

    # Add the item to the result map
    result_map.append(item_element)

# Create an ElementTree object with the result map
result_tree = ET.ElementTree(result_map)

# Write the results to a new XML file
result_tree.write("reduce_1.xml", encoding="utf-8")

