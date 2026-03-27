<?php
$name = $email = $gender = $phone = $website = $password = $confirm_password = "";
$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = $passwordErr = $confirmErr = $termsErr = "";
$success = false;
$attempts = 0;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attempts = isset($_POST['attempts']) ? (int)$_POST['attempts'] : 0;
    $attempts++;
    
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }
    
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
    
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }
    
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }
    
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        }
    }
    
    if (empty($_POST["confirm_password"])) {
        $confirmErr = "Please confirm your password";
    } else {
        $confirm_password = $_POST["confirm_password"];
        if ($password !== $confirm_password) {
            $confirmErr = "Passwords do not match";
        }
    }
    
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }
    
    if (empty($nameErr) && empty($emailErr) && empty($genderErr) && 
        empty($phoneErr) && empty($websiteErr) && empty($passwordErr) && 
        empty($confirmErr) && empty($termsErr)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Validation Lab</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="url"] {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        input[type="radio"] { margin-right: 10px; }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .attempts { background: #e7f3ff; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>PHP Form Validation Lab</h1>
    
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="attempts">
            <strong>Submission attempt: <?= $attempts ?></strong>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
        <div class="form-group">
            <label for="name">Name *:</label>
            <input type="text" id="name" name="name" value="<?= $name ?>">
            <div class="error"><?= $nameErr ?></div>
        </div>
        
        <div class="form-group">
            <label for="email">Email *:</label>
            <input type="email" id="email" name="email" value="<?= $email ?>">
            <div class="error"><?= $emailErr ?></div>
        </div>
        
        <div class="form-group">
            <label>Gender *:</label>
            <input type="radio" id="male" name="gender" value="Male" <?= $gender == "Male" ? "checked" : "" ?>>
            <label for="male" style="display: inline; font-weight: normal;">Male</label>
            <input type="radio" id="female" name="gender" value="Female" <?= $gender == "Female" ? "checked" : "" ?>>
            <label for="female" style="display: inline; font-weight: normal;">Female</label>
            <input type="radio" id="other" name="gender" value="Other" <?= $gender == "Other" ? "checked" : "" ?>>
            <label for="other" style="display: inline; font-weight: normal;">Other</label>
            <div class="error"><?= $genderErr ?></div>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number *:</label>
            <input type="tel" id="phone" name="phone" value="<?= $phone ?>" placeholder="+1-555-123-4567">
            <div class="error"><?= $phoneErr ?></div>
        </div>
        
        <div class="form-group">
            <label for="website">Website (optional):</label>
            <input type="url" id="website" name="website" value="<?= $website ?>" placeholder="https://example.com">
            <div class="error"><?= $websiteErr ?></div>
        </div>
        
        <div class="form-group">
            <label for="password">Password * (min 8 chars):</label>
            <input type="password" id="password" name="password" value="">
            <div class="error"><?= $passwordErr ?></div>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password *:</label>
            <input type="password" id="confirm_password" name="confirm_password" value="">
            <div class="error"><?= $confirmErr ?></div>
        </div>
        
        <div class="form-group">
            <input type="checkbox" id="terms" name="terms" <?= isset($_POST['terms']) ? "checked" : "" ?>>
            <label for="terms" style="display: inline; font-weight: normal;">I agree to the <a href="#">terms and conditions</a> *</label>
            <div class="error"><?= $termsErr ?></div>
        </div>
        
        <input type="hidden" name="attempts" value="<?= $attempts ?>">
        
        <button type="submit">Submit Form</button>
    </form>
    
    <?php if ($success): ?>
        <div class="success">
            <h2>✓ Form submitted successfully!</h2>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Gender:</strong> <?= $gender ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
            <?php if (!empty($website)): ?>
                <p><strong>Website:</strong> <a href="<?= $website ?>" target="_blank"><?= $website ?></a></p>
            <?php endif; ?>
            <p>Thank you for your submission!</p>
        </div>
    <?php endif; ?>
</body>
</html>