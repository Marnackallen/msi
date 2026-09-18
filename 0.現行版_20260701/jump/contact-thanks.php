<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了しました - 株式会社ミユキット</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Noto+Sans+JP:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <style>
        body, p, h1, h2 {
            font-feature-settings: "palt" 1;
        }

        .thanks-section {
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 5vw;
        }

        .thanks-icon {
            font-size: 3.5rem;
            color: var(--accent-orange);
            margin-bottom: 1.5rem;
        }

        .thanks-section h1 {
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 900;
            color: var(--primary-text);
            margin-bottom: 1rem;
        }

        .thanks-section p {
            color: var(--secondary-text);
            line-height: 1.8;
            max-width: 500px;
            margin-bottom: 2.5rem;
        }

        .thanks-back-btn {
            display: inline-block;
            background-color: var(--accent-orange);
            color: #fff !important;
            padding: 1rem 3rem;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(245, 166, 35, 0.4);
            transition: all 0.3s ease;
        }

        .thanks-back-btn:hover {
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <main class="thanks-section">
        <div class="thanks-icon"><i class="fa-solid fa-circle-check"></i></div>
        <h1>お問い合わせありがとうございました</h1>
        <p>
            この度はご相談いただき誠にありがとうございます。<br>
            内容を確認の上、担当者より2〜3営業日以内にご連絡いたします。<br>
            今しばらくお待ちくださいませ。
        </p>
        <a href="lp-video.html" class="thanks-back-btn">トップページへ戻る</a>
    </main>
</body>

</html>
