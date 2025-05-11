
<div class="container mt-4">
    <h3>文字分詞</h3>
    <input type="text" id="input-text" class="form-control" placeholder="請輸入文本">
    <button id="tokenize-btn" class="btn btn-primary mt-2">開始分詞</button>

    <div id="tokenized-output" class="mt-3"></div>
</div>

<script>
    document.getElementById('tokenize-btn').addEventListener('click', function () {
        const text = document.getElementById('input-text').value;

        console.log("發送的文本:", text);  // 這裡檢查文本是否正確

        if (!text) {
            alert('請輸入文字');
            return;
        }

        fetch('http://localhost:5000/tokenize', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ text: text })
        })
            .then(response => response.json())
            .then(data => {
                console.log("返回結果:", data);  // 檢查返回的資料
                const outputDiv = document.getElementById('tokenized-output');
                // 假設返回的 data 是 {tokens: [...]}
                if (data.tokens && Array.isArray(data.tokens)) {
                    // 只顯示 `word` 屬性的值，並用 `|` 分隔
                    const tokenWords = data.tokens.map(token => token.word).join(' | ');
                    outputDiv.innerHTML = '<strong>分詞結果：</strong>' + tokenWords;
                } else {
                    outputDiv.innerHTML = '<strong>無分詞結果</strong>';
                }
            })
            .catch(error => {
                console.error('錯誤:', error);
                alert('無法連接 Flask API，請確認後端是否有啟動');
            });
    });

</script>
