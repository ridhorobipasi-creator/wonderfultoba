import re

with open('resources/views/tour/package-detail.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace no-scrollbar with custom-scroll
content = content.replace('max-h-[85vh] overflow-y-auto no-scrollbar', 'max-h-[85vh] overflow-y-auto custom-scroll')

# Add custom scroll CSS right before the layout
css = '''
    <style>
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
'''
if '.custom-scroll::-webkit-scrollbar' not in content:
    content = content.replace('<!-- Booking Form Sidebar (Sticky) -->', css + '\n        <!-- Booking Form Sidebar (Sticky) -->')

with open('resources/views/tour/package-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')
