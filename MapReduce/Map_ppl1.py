# -*- coding: utf-8 -*-
"""
Created on Wed Oct 18 11:57:25 2023

@author: Ivan
"""

import xml.etree.ElementTree as ET

# Create an empty map element
map_element = ET.Element("map")

# Specify the input XML file
input_file = 'people1.xml'

# Parse the input XML file
tree = ET.parse(input_file)
root = tree.getroot()

# Iterate over the 'name' elements and create item elements
for person in root:
    name = person.find('name').text

    # Create an item element
    item_element = ET.SubElement(map_element, "item")

    # Add the 'name' and 'value' elements to the item
    name_element = ET.SubElement(item_element, "name")
    name_element.text = name

    value_element = ET.SubElement(item_element, "value")
    value_element.text = "1"

# Create an ElementTree object with the map element
result_tree = ET.ElementTree(map_element)

# Write the results to an XML file
result_tree.write("Map_1.xml", encoding="utf-8")
