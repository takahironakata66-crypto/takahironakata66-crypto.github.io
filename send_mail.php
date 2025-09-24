<?php
// 日本語メール対応
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
        // HTMLとして返す（日本語もOK）
        echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>エラー</title></head><body style='font-family: sans-serif; background:#f9fafb; color:#333; padding:2em;'><h2>入力内容に誤りがあります。</h2><p>すべての項目を正しく入力してください。</p></body></html>";
        exit;
    }

    // ヘッダインジェクション対策
    $name    = str_replace(array("\r", "\n"), '', $name);
    $email   = str_replace(array("\r", "\n"), '', $email);
    $subject = str_replace(array("\r", "\n"), '', $subject);

    $to = "flowrise2025@gmail.com";
    $email_subject = "お問い合わせ: " . $subject;

    $email_body = "お名前: {$name}\n";
    $email_body .= "メールアドレス: {$email}\n\n";
    $email_body .= "お問い合わせ内容:\n{$message}\n";

    $headers = "From: {$name} <{$email}>\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // メール送信
    $result = mb_send_mail($to, $email_subject, $email_body, $headers);

    if ($result) {
        echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>送信完了</title></head><body style='font-family: sans-serif; background:#f9fafb; color:#333; padding:2em;'><h2>お問い合わせありがとうございます。</h2><p>送信が完了しました。担当者よりご連絡いたします。</p><a href='index.html'>トップページへ戻る</a></body></html>";
    } else {
        echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>送信エラー</title></head><body style='font-family: sans-serif; background:#f9fafb; color:#333; padding:2em;'><h2>送信中に問題が発生しました。</h2><p>時間をおいて再度お試しください。</p><a href='index.html'>トップページへ戻る</a></body></html>";
    }
} else {
    // POST以外は無効
    echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>無効なリクエスト</title></head><body style='font-family: sans-serif; background:#f9fafb; color:#333; padding:2em;'><h2>無効なリクエストです。</h2><p>フォームからご送信ください。</p><a href='index.html'>トップページへ戻る</a></body></html>";
}
?>
