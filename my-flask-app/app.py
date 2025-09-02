from flask import Flask, request, jsonify
import jieba.posseg as pseg
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # 允許跨域，供 Laravel 前端存取

@app.route('/tokenize', methods=['POST'])
def tokenize():
    data = request.get_json()

    title = data.get('title', '')
    paragraphs = data.get('paragraphs', [])

    # 對標題進行斷詞
    title_tokens = [ {"word": word, "flag": flag} for word, flag in pseg.cut(title) ]

    # 對每一段落進行斷詞
    paragraph_results = {}
    for para in paragraphs:
        para_id = str(para['id'])
        para_text = para['text']
        tokens = [ {"word": word, "flag": flag} for word, flag in pseg.cut(para_text) ]
        paragraph_results[para_id] = tokens

    return jsonify({
        "title": title_tokens,
        "paragraphs": paragraph_results
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)