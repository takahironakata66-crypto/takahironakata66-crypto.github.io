<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = strip_tags(trim($_POST["name"]));
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $message = trim($_POST["message"]);

  if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo "入力内容に問題があります。";
    exit;
  }

  $to = "flowrise2025@gmail.com";
  $subject = "お問い合わせフォームからのメッセージ";
  $body = "名前: $name\nメール: $email\nメッセージ:\n$message";
  $headers = "From: $name <$email>";

  if (mail($to, $subject, $body, $headers)) {
    http_response_code(200);
    echo "メッセージを送信しました。";
  } else {
    http_response_code(500);
    echo "送信中にエラーが発生しました。";
  }
} else {
  http_response_code(403);
  echo "フォームから送信してください。";
}
?>
