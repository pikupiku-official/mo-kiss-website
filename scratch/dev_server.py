import http.server
import socketserver
import re
import os

PORT = 8080

def get_digit_svg(digit):
    segments = {
        'a': [4, 3, 12, 2],
        'f': [3, 5, 2, 7],
        'b': [15, 5, 2, 7],
        'g': [5, 12, 10, 2],
        'e': [3, 14, 2, 7],
        'c': [15, 14, 2, 7],
        'd': [4, 21, 12, 2]
    }
    digits_map = {
        '0': ['a', 'b', 'c', 'd', 'e', 'f'],
        '1': ['b', 'c'],
        '2': ['a', 'b', 'g', 'e', 'd'],
        '3': ['a', 'b', 'g', 'c', 'd'],
        '4': ['f', 'g', 'b', 'c'],
        '5': ['a', 'f', 'g', 'c', 'd'],
        '6': ['a', 'f', 'g', 'e', 'c', 'd'],
        '7': ['a', 'b', 'c'],
        '8': ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
        '9': ['a', 'b', 'c', 'd', 'f', 'g']
    }
    active = digits_map.get(digit, digits_map['0'])
    
    svg = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="25" viewBox="0 0 20 25">'
    svg += '<rect width="20" height="25" fill="#000000" stroke="#ffd700" stroke-width="1"/>'
    
    for name, coords in segments.items():
        color = '#ff0055' if name in active else '#25050e'
        svg += f'<rect x="{coords[0]}" y="{coords[1]}" width="{coords[2]}" height="{coords[3]}" fill="{color}"/>'
    
    svg += '</svg>'
    return svg

class MockPHPHandler(http.server.SimpleHTTPRequestHandler):
    def do_GET(self):
        # 1. カウンターAPIの横取り
        if 'api/counter.php' in self.path:
            digit = '0'
            match = re.search(r'digit=(\d)', self.path)
            if match:
                digit = match.group(1)
            
            svg_content = get_digit_svg(digit)
            self.send_response(200)
            self.send_header('Content-Type', 'image/svg+xml')
            self.send_header('Content-Length', len(svg_content))
            self.end_headers()
            self.wfile.write(svg_content.encode('utf-8'))
            return
            
        # 2. PHPファイルのモック処理
        filename = self.path.split('?')[0].strip('/')
        if not filename:
            filename = 'index.html'
            
        if filename.endswith('.php') and os.path.exists(filename):
            try:
                with open(filename, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                content = self.process_php_mock(filename, content)
                
                response_data = content.encode('utf-8')
                self.send_response(200)
                self.send_header('Content-Type', 'text/html; charset=utf-8')
                self.send_header('Content-Length', len(response_data))
                self.end_headers()
                self.wfile.write(response_data)
                return
            except Exception as e:
                self.send_error(500, f"Error rendering PHP mock: {str(e)}")
                return
                
        # 3. 通常の静的ファイル処理
        return super().do_GET()

    def process_php_mock(self, filename, content):
        # require_once などのPHPインフラブロックを除去
        content = re.sub(r'<\?php\s+/\*\*.*?\*/.*?require_once.*?\?>', '', content, flags=re.DOTALL)
        content = re.sub(r'<\?php.*?require_once.*?\?>', '', content, flags=re.DOTALL)
        
        # displayCounter置換 (SVGを利用した6桁ダミー表示)
        def counter_repl(match):
            html = '<span class="counter">'
            digits = '000033'
            for d in digits:
                html += f'<img src="api/counter.php?digit={d}" alt="{d}" width="20" height="25" style="margin: 0 1px;">'
            html += '</span>'
            return html
            
        content = re.sub(r'<\?php\s+echo\s+displayCounter\(.*?\);\s+\?>', counter_repl, content)
        
        # 訪問者数の数字置換
        content = re.sub(r'<\?php\s+echo\s+number_format\(\$accessCount\);\s+\?>', '33', content)
        
        # rand(...)
        content = re.sub(r'<\?php\s+echo\s+rand\(1,\s*5\);\s+\?>', '3', content)
        content = re.sub(r'<\?php\s+echo\s+rand\(5,\s*20\);\s+\?>', '12', content)
        
        # What's New 関連のPHP変数をダミーで置き換え (もうセクションはないが変数をクリーンアップ)
        content = re.sub(r'<\?php\s+if\s+\(empty\(\$newsList\)\).*?<\?php\s+endif;\s+\?>', '', content, flags=re.DOTALL)
        
        # キャラクター一覧の表示をモックデータに置換 (character.php) - 新しい4名のキャラクター情報
        char_mock_html = """
        <div class="character-box">
            <div class="character-name">
                愛沼 桃子 <span class="character-kana">あいぬま ももこ</span>
            </div>
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="200" valign="top" align="center">
                        <div style="width: 150px; height: 200px; background: #1a0813; border: 2px dashed #ff66cc; display: flex; align-items: center; justify-content: center; color: #ff7f00; font-size: 11px; font-weight: normal;">
                            [ 立ち絵準備中 ]
                        </div>
                    </td>
                    <td valign="top">
                        <div class="character-info">
                            <p><strong>学年:</strong> 二年生</p>
                            <p><strong>部活:</strong> バドミントン部</p>
                            <p style="margin-top: 15px; line-height: 1.4; color: #1c1015;">
                                健やかな身体の幼馴染。テニス部所属。ネアカで家庭的なので実はモテるが、当の本人は大好きな家族と出掛けたり喫茶店を巡ったりで隙が無い。
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="character-box">
            <div class="character-name">
                舞田 沙那子 <span class="character-kana">まいた さなこ</span>
            </div>
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="200" valign="top" align="center">
                        <div style="width: 150px; height: 200px; background: #1a0813; border: 2px dashed #ff66cc; display: flex; align-items: center; justify-content: center; color: #ff7f00; font-size: 11px; font-weight: normal;">
                            [ 立ち絵準備中 ]
                        </div>
                    </td>
                    <td valign="top">
                        <div class="character-info">
                            <p><strong>学年:</strong> 三年生</p>
                            <p><strong>部活:</strong> 帰宅部</p>
                            <p style="margin-top: 15px; line-height: 1.4; color: #1c1015;">
                                遅刻をした日、正門で出会った先輩。話しかけても返事は冷たく、ひとりが好きみたいだ。帰りの電車でゲームボーイカラーをしている。
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="character-box">
            <div class="character-name">
                桔梗 美鈴 <span class="character-kana">ききょう みすず</span>
            </div>
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="200" valign="top" align="center">
                        <div style="width: 150px; height: 200px; background: #1a0813; border: 2px dashed #ff66cc; display: flex; align-items: center; justify-content: center; color: #ff7f00; font-size: 11px; font-weight: normal;">
                            [ 立ち絵準備中 ]
                        </div>
                    </td>
                    <td valign="top">
                        <div class="character-info">
                            <p><strong>学年:</strong> 三年生</p>
                            <p><strong>部活:</strong> 元吹奏楽部</p>
                            <p style="margin-top: 15px; line-height: 1.4; color: #1c1015;">
                                抜群のルックスを持つ高嶺の花の先輩。吹奏楽部でコントラバスを弾いていた。いつも明るく楽しそうでそれ故人気が高いが、恋愛に関しては奥手。
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="character-box">
            <div class="character-name">
                増田 <span class="character-kana">ますだ</span>
            </div>
            <table width="100%" cellpadding="5" cellspacing="0">
                <tr>
                    <td width="200" valign="top" align="center">
                        <div style="width: 150px; height: 200px; background: #1a0813; border: 2px dashed #ff66cc; display: flex; align-items: center; justify-content: center; color: #ff7f00; font-size: 11px; font-weight: normal;">
                            [ 立ち絵準備中 ]
                        </div>
                    </td>
                    <td valign="top">
                        <div class="character-info">
                            <p><strong>学年:</strong> 二年生</p>
                            <p><strong>部活:</strong> 野球部（元）</p>
                            <p style="margin-top: 15px; line-height: 1.4; color: #1c1015;">
                                クラスのムードメーカーで主人公の親友。野球部を辞めた現在は、ロックバンドで一旗揚げるべく歌を特訓中。とにかくモテない。
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        """
        content = re.sub(r'<\?php\s+if\s+\(empty\(\$characters\)\).*?<\?php\s+endif;\s+\?>', char_mock_html, content, flags=re.DOTALL)
        
        # SYSTEMコンテンツの置換 (system.php)
        system_mock_html = """
        <div style="line-height: 1.4; color: #1c1015;">
            <h3>■ 基本システム</h3>
            <p>本作は、テキストを読み進めながら選択肢を選ぶことで物語が分岐する、恋愛アドベンチャーゲームです。</p>
            <p><strong>・終末カウントダウンシステム</strong><br>
            ゲーム内時間は1999年5月31日から始まり、1日ごとに世界の終末, 7月31日へと近づいていきます。<br>
            限られた時間でどう行動していくかが、少女たちとのエンディングに影響します。</p>
            <p><strong>・美麗なCGと演出</strong><br>
            1999年風の色調補正・画質加工によって彩られる「あの頃の景色」が物語を劇的に彩ります。</p>
            <p><strong>・動作環境</strong><br>
            動作環境：Steam</p>
        </div>
        """
        if filename == 'system.php':
            content = re.sub(r'<\?php\s+if\s+\(\$page\).*?<\?php\s+endif;\s+\?>', system_mock_html, content, flags=re.DOTALL)
            
        # h(...) を除去
        content = re.sub(r'<\?php\s+echo\s+h\((.*?)\);\s+\?>', r'\1', content)
        content = re.sub(r'<\?php\s+echo\s+(.*?);\s+\?>', r'\1', content)
        content = re.sub(r'<\?php.*?\?>', '', content, flags=re.DOTALL)
        
        return content

if __name__ == '__main__':
    handler = MockPHPHandler
    try:
        os.chdir(r'c:\Users\モーキス\mo-kiss-website')
    except:
        pass
    
    with socketserver.TCPServer(("", PORT), handler) as httpd:
        print(f"Serving MOKISS Mock Server on http://localhost:{PORT}")
        httpd.serve_forever()
