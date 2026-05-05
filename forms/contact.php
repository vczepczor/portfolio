<?php
// TADY ZMĚŇ E-MAIL NA SVŮJ:
$recipient = "vaclavczepczor@gmail.com";

// Zjistíme, zda byl formulář odeslán metodou POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Získání a očištění dat z formuláře (ochrana před škodlivým kódem)
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Kontrola, zda jsou všechna pole vyplněná a e-mail je validní
    if (empty($name) OR empty($subject) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Vyplňte prosím všechna pole správně a zkontrolujte svůj e-mail.";
        exit;
    }

    // Sestavení obsahu e-mailu
    $email_content = "Nová zpráva z kontaktního formuláře:\n\n";
    $email_content .= "Jméno: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Zpráva:\n$message\n";

    // Nastavení hlaviček (od koho e-mail přišel)
    $email_headers = "From: $name <$email>";

    // Samotné odeslání e-mailu pomocí funkce mail()
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo "OK"; // Třída "php-email-form" v některých šablonách očekává přesně tuto odpověď pro zobrazení úspěchu
    } else {
        http_response_code(500);
        echo "Jejda! Něco se pokazilo a zprávu se nepodařilo odeslat. Zkuste to prosím později.";
    }

} else {
    // Pokud na soubor někdo přistoupí přímo (ne přes formulář)
    http_response_code(403);
    echo "K tomuto souboru nemáte přístup.";
}
?>