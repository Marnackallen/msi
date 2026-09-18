<?php
// contact.php — お問い合わせ・無料相談フォーム
// 送信先メールアドレス（必要に応じて変更してください）
$to_email = "info@miucuit.jp";

$errors = [];
$values = [
    'name'    => '',
    'company' => '',
    'email'   => '',
    'tel'     => '',
    'type'    => '',
    'timing'  => '',
    'budget'  => '',
    'detail'  => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- ハニーポット（スパム対策：人には見えない入力欄） ----
    $honeypot = trim($_POST['website'] ?? '');

    // ---- 入力値の取得 ----
    foreach ($values as $key => $default) {
        $values[$key] = trim($_POST[$key] ?? '');
    }

    // ---- バリデーション ----
    if ($honeypot !== '') {
        // ボット判定。エラーは出さずサイレントに拒否。
        $errors[] = 'invalid_submission';
    }
    if ($values['name'] === '') {
        $errors['name'] = 'お名前を入力してください。';
    }
    if ($values['email'] === '') {
        $errors['email'] = 'メールアドレスを入力してください。';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'メールアドレスの形式が正しくありません。';
    }
    if ($values['type'] === '') {
        $errors['type'] = 'ご相談内容を選択してください。';
    }

    // ヘッダーインジェクション対策：改行を含む値を弾く
    foreach ($values as $key => $val) {
        if (preg_match('/[\r\n]/', $val)) {
            $errors[$key] = '入力内容に不正な文字が含まれています。';
        }
    }

    if (empty($errors)) {
        // ---- メール本文作成 ----
        $type_labels = [
            'shooting' => '撮影のみ',
            'editing'  => '編集のみ',
            'full'     => '企画から撮影・編集まで',
            'other'    => 'その他',
        ];
        $budget_labels = [
            'b1' => '〜10万円',
            'b2' => '10〜30万円',
            'b3' => '30〜50万円',
            'b4' => '50万円以上',
            'b5' => '未定',
        ];

        $subject = "【LPお問い合わせ】{$values['name']}様よりご相談";

        $body  = "LPサイトより、映像制作のご相談を受け付けました。\n";
        $body .= "----------------------------------------\n";
        $body .= "お名前　　　　：{$values['name']}\n";
        $body .= "会社名・屋号　：{$values['company']}\n";
        $body .= "メールアドレス：{$values['email']}\n";
        $body .= "電話番号　　　：{$values['tel']}\n";
        $body .= "ご相談内容　　：" . ($type_labels[$values['type']] ?? $values['type']) . "\n";
        $body .= "希望撮影時期　：{$values['timing']}\n";
        $body .= "ご予算感　　　：" . ($budget_labels[$values['budget']] ?? $values['budget']) . "\n";
        $body .= "詳細・ご要望　：\n{$values['detail']}\n";
        $body .= "----------------------------------------\n";
        $body .= "送信日時：" . date('Y-m-d H:i:s') . "\n";
        $body .= "送信元IP：" . ($_SERVER['REMOTE_ADDR'] ?? '') . "\n";

        $headers = [];
        $headers[] = "From: no-reply@miucuit.jp";
        $headers[] = "Reply-To: " . $values['email'];
        $headers[] = "Content-Type: text/plain; charset=UTF-8";

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        $encoded_subject = mb_encode_mimeheader($subject, "UTF-8", "B", "\n");

        $sent = mail($to_email, $encoded_subject, $body, implode("\r\n", $headers));

        if ($sent) {
            header("Location: contact-thanks.php");
            exit;
        } else {
            $errors['send'] = 'メールの送信に失敗しました。時間をおいて再度お試しいただくか、お電話にてご連絡ください。';
        }
    }
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>まずは無料で相談する - 株式会社ミユキット</title>
    <meta name="description" content="映像制作のご相談・お見積りは無料です。お気軽にお問い合わせください。">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Noto+Sans+JP:wght@400;500;700;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <style>
        body, p, h1, h2, h3, h4, h5, h6, li, span, div {
            word-break: auto-phrase;
            font-feature-settings: "palt" 1;
        }

        .form-section {
            padding: 8rem 5vw 6rem;
            max-width: 720px;
            margin: 0 auto;
        }

        .form-section h1 {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            font-weight: 900;
            color: var(--primary-text);
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .form-section .lead {
            color: var(--secondary-text);
            text-align: center;
            margin-bottom: 3rem;
            line-height: 1.8;
        }

        .contact-form {
            background: #fff;
            padding: 2.5rem;
            border-radius: var(--border-radius-lg, 16px);
            box-shadow: var(--shadow-soft, 0 4px 20px rgba(0,0,0,0.06));
        }

        @media (max-width: 600px) {
            .contact-form { padding: 1.5rem; }
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            color: var(--primary-text);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: #e0533d;
            font-size: 0.75rem;
            font-weight: 700;
            margin-left: 0.5rem;
            border: 1px solid #e0533d;
            border-radius: 4px;
            padding: 0.1rem 0.4rem;
        }

        .form-group label .optional {
            color: var(--secondary-text);
            font-size: 0.75rem;
            font-weight: 500;
            margin-left: 0.5rem;
            border: 1px solid var(--lp-border, #ccc);
            border-radius: 4px;
            padding: 0.1rem 0.4rem;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-family: inherit;
            background: #fafafa;
            transition: border-color 0.2s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent-orange);
            background: #fff;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 500;
            cursor: pointer;
        }

        .radio-group input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: var(--accent-orange);
        }

        .field-error {
            color: #e0533d;
            font-size: 0.85rem;
            margin-top: 0.4rem;
        }

        .form-error-banner {
            background: #fdecea;
            border: 1px solid #e0533d;
            color: #c0392b;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .submit-btn {
            display: block;
            width: 100%;
            background-color: var(--accent-orange);
            color: #fff;
            border: none;
            padding: 1.2rem;
            font-size: 1.2rem;
            font-weight: 900;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(245, 166, 35, 0.4);
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(245, 166, 35, 0.6);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 2rem;
            color: var(--secondary-text);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-link:hover {
            color: var(--accent-orange);
        }

        /* ハニーポット欄は非表示 */
        .hp-field {
            position: absolute;
            left: -9999px;
            top: -9999px;
        }
    </style>
</head>

<body>

    <main class="form-section">
        <h1>まずは無料で相談する</h1>
        <p class="lead">
            映像制作のプラン選びから、構成のご提案、撮影スケジュールまで丁寧にご案内します。<br>
            ご相談・お見積りは無料です。下記フォームよりお気軽にお問い合わせください。
        </p>

        <?php if (isset($errors['send'])): ?>
            <div class="form-error-banner"><?= h($errors['send']) ?></div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)): ?>
            <div class="form-error-banner">入力内容にエラーがあります。ご確認の上、再度送信してください。</div>
        <?php endif; ?>

        <form class="contact-form" action="contact.php" method="post" novalidate>

            <!-- ハニーポット（人間には見えない） -->
            <div class="form-group hp-field" aria-hidden="true">
                <label for="website">Website</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="name">お名前<span class="required">必須</span></label>
                <input type="text" id="name" name="name" value="<?= h($values['name']) ?>" required>
                <?php if (isset($errors['name'])): ?><p class="field-error"><?= h($errors['name']) ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="company">会社名・屋号<span class="optional">任意</span></label>
                <input type="text" id="company" name="company" value="<?= h($values['company']) ?>">
            </div>

            <div class="form-group">
                <label for="email">メールアドレス<span class="required">必須</span></label>
                <input type="email" id="email" name="email" value="<?= h($values['email']) ?>" required>
                <?php if (isset($errors['email'])): ?><p class="field-error"><?= h($errors['email']) ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tel">電話番号<span class="optional">任意</span></label>
                <input type="tel" id="tel" name="tel" value="<?= h($values['tel']) ?>">
            </div>

            <div class="form-group">
                <label>ご相談内容<span class="required">必須</span></label>
                <div class="radio-group">
                    <?php
                    $type_options = [
                        'shooting' => '撮影のみ',
                        'editing'  => '編集のみ',
                        'full'     => '企画から撮影・編集まで',
                        'other'    => 'その他',
                    ];
                    foreach ($type_options as $val => $label):
                        $checked = ($values['type'] === $val) ? 'checked' : '';
                    ?>
                        <label>
                            <input type="radio" name="type" value="<?= $val ?>" <?= $checked ?> required>
                            <?= h($label) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($errors['type'])): ?><p class="field-error"><?= h($errors['type']) ?></p><?php endif; ?>
            </div>

            <div class="form-group">
                <label for="timing">ご希望の撮影時期<span class="optional">任意</span></label>
                <input type="text" id="timing" name="timing" placeholder="例：2026年9月頃" value="<?= h($values['timing']) ?>">
            </div>

            <div class="form-group">
                <label for="budget">ご予算感<span class="optional">任意</span></label>
                <select id="budget" name="budget">
                    <option value="">選択してください</option>
                    <?php
                    $budget_options = [
                        'b1' => '〜10万円',
                        'b2' => '10〜30万円',
                        'b3' => '30〜50万円',
                        'b4' => '50万円以上',
                        'b5' => '未定',
                    ];
                    foreach ($budget_options as $val => $label):
                        $selected = ($values['budget'] === $val) ? 'selected' : '';
                    ?>
                        <option value="<?= $val ?>" <?= $selected ?>><?= h($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="detail">詳細・ご要望<span class="optional">任意</span></label>
                <textarea id="detail" name="detail"><?= h($values['detail']) ?></textarea>
            </div>

            <button type="submit" class="submit-btn">この内容で送信する</button>
        </form>

        <a href="lp-video.html" class="back-link">&larr; トップページへ戻る</a>
    </main>

</body>

</html>
