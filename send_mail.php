<?php
mb_language("Japanese");
mb_internal_encoding("UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 入力の取得とサニタイズ
    $name    = isset($_POST["name"])    ? strip_tags(trim($_POST["name"]))    : '';
    $email   = isset($_POST["email"])   ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST["subject"]) ? strip_tags(trim($_POST["subject"])) : '';
    $message = isset($_POST["message"]) ? trim($_POST["message"])             : '';

    // 入力チェック
    if (
        empty($name) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        empty($subject) ||
        empty($message)
    ) {
        echo "入力内容に誤りがあります。すべての項目を正しく入力してください。";
        exit;
    }

    // ヘッダインジェクション対策
    $name    = str_replace(array("\r", "\n"), '', $name);
    $email   = str_replace(array("\r", "\n"), '', $email);
    $subject = str_replace(array("\r", "\n"), '', $subject);

    $to = "flowrise2025@gmail.com";

    // 件名にMIMEエンコード（ISO-2022-JPは省略してUTF-8にする場合）
    $email_subject = "お問い合わせ: " . $subject;

    $email_body = "お名前: {$name}\n";
    $email_body .= "メールアドレス: {$email}\n\n";
    $email_body .= "お問い合わせ内容:\n{$message}\n";

    // ヘッダーはUTF-8に対応した形式で
    $headers = "From: {$name} <{$email}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // mb_send_mail 使用して送信テスト（動かない場合はmail関数に戻す）
    if (!function_exists('mb_send_mail') || !mb_send_mail($to, $email_subject, $email_body, $headers)) {
        // mb_send_mailがなかったり失敗したらmailで送信
        $result = mail($to, $email_subject, $email_body, $headers);
    } else {
        $result = true;
    }

    if ($result) {
        echo "お問い合わせありがとうございます。送信が完了しました。";
    } else {
        echo "送信中に問題が発生しました。時間をおいて再度お試しください。";
    }
} else {
    echo "無効なリクエストです。フォームからご送信ください。";
}
