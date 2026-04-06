import zipfile
import xml.etree.ElementTree as ET
import os

docx_path = "LinkedIn_Product_Plan_v2_edited (1).docx"
temp_dir = os.path.join(os.environ['TEMP'], 'docx_extract')

# Extract docx
with zipfile.ZipFile(docx_path, 'r') as zip_ref:
    zip_ref.extractall(temp_dir)

# Read document.xml
doc_path = os.path.join(temp_dir, 'word', 'document.xml')
tree = ET.parse(doc_path)
root = tree.getroot()

# Extract all text
ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
text_elements = root.findall('.//w:t', ns)
full_text = ''.join([elem.text for elem in text_elements if elem.text])
print(full_text)
