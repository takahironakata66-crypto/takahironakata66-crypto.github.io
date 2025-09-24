<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject) || empty($message)) {
        echo "入力内容に誤りがあります。";
        exit;
    }

    $to = "flowrise2025@gmail.com";
    $email_subject = "お問い合わせ: $subject";

    $email_body = "お名前: $name\n";
    $email_body .= "メールアドレス: $email\n\n";
    $email_body .= "お問い合わせ内容:\n$message\n";

    $headers = "From: $name <$email>";

    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "お問い合わせありがとうございます。送信が完了しました。";
    } else {
        echo "送信中に問題が発生しました。";
    }
} else {
    echo "無効なリクエストです。";
}
