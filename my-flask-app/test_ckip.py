from ckiptagger import WS
ws = WS("./data")  # 載入模型
sentence_list = ["中央研究院是台灣的最高學術機構"]
word_sentence_list = ws(sentence_list)
print("CKIP 斷詞結果：", word_sentence_list)