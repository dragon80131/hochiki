#!/usr/bin/env python3
"""Replace hardcoded 空き/枠越 slot labels with _SLOT_LABEL_* constants."""
import re
import sys

FILES = [
    'httpdocs/s_format.php',
    'httpdocs/sh_list.php',
    'httpdocs/doc/s_make_kanryo_hensyu.php',
    'httpdocs/doc/s_make_kotei_EXCEL.php',
]

def transform(content: str) -> str:
    # Assignments
    content = content.replace('[] = "空き"', '[] = _SLOT_LABEL_AKI')
    content = content.replace("[] = '空き'", '[] = _SLOT_LABEL_AKI')
    content = content.replace('[] = "枠越"', '[] = _SLOT_LABEL_WAKUOVER')
    content = content.replace("[] = '枠越'", '[] = _SLOT_LABEL_WAKUOVER')
    content = content.replace("= '枠越'", '= _SLOT_LABEL_WAKUOVER')

    # Comparisons (display branches)
    content = re.sub(
        r'(\$\{\'Waku\' \. \$WakuName \. \'Room\'\}\[\$\{\$WakuName \. "index"\}\]) == "空き"',
        r'isSlotLabelAki(\1)',
        content,
    )
    content = re.sub(
        r'(\$\{\'Waku\' \. \$WakuName \. \'Room\'\}\[\$\{\$WakuName \. "index"\}\]) == "枠越"',
        r'isSlotLabelWakuover(\1)',
        content,
    )

    # hensyu / excel wKoteihyouEX comparisons
    content = re.sub(r'\$wKoteihyouEX\[\$m\]=="空き"', 'isSlotLabelAki($wKoteihyouEX[$m])', content)
    content = re.sub(r'\$wKoteihyouEX\[\$m\]=="枠越"', 'isSlotLabelWakuover($wKoteihyouEX[$m])', content)
    content = re.sub(r'\$wKoteihyouEX\[\$k\] == "空き"', 'isSlotLabelAki($wKoteihyouEX[$k])', content)
    content = re.sub(r'\$wKoteihyouEX\[\$k\] == "枠越"', 'isSlotLabelWakuover($wKoteihyouEX[$k])', content)
    content = re.sub(r'\$wKoteihyouEX\[\$k\]=="空き"', 'isSlotLabelAki($wKoteihyouEX[$k])', content)
    content = re.sub(r'\$wKoteihyouEX\[\$k\]=="枠越"', 'isSlotLabelWakuover($wKoteihyouEX[$k])', content)

    return content

def main():
    root = sys.argv[1] if len(sys.argv) > 1 else '.'
    for rel in FILES:
        path = f"{root}/{rel}".replace('/', '\\') if '\\' in root else f"{root}/{rel}"
        import os
        path = os.path.normpath(os.path.join(root, rel))
        with open(path, 'r', encoding='utf-8') as f:
            original = f.read()
        updated = transform(original)
        if updated != original:
            with open(path, 'w', encoding='utf-8', newline='\n') as f:
                f.write(updated)
            print('updated', rel)
        else:
            print('unchanged', rel)

if __name__ == '__main__':
    main()
