import re

with open('resources/views/tour/package-detail.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Revert the order classes
content = content.replace('order-1 md:order-1', '')
content = content.replace('order-3 md:order-3', '')
content = content.replace('order-2 md:order-2 ', '')

# 2. Extract the sidebar
sidebar_start = content.find('        <!-- Booking Form Sidebar (Sticky) -->')
# Find the next section closing tag after sidebar start
section_end = content.find('    </section>', sidebar_start)

# Extract sidebar including its whitespace
sidebar_block = content[sidebar_start:section_end]

# Remove sidebar from the bottom
content = content[:sidebar_start] + content[section_end:]

# 3. Find the spot to insert it (right before Content Part)
content_part_start = content.find('        <!-- Content Part -->')

# Insert the sidebar before Content Part
content = content[:content_part_start] + sidebar_block + '\n' + content[content_part_start:]

with open('resources/views/tour/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')
