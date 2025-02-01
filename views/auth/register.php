<?php
$pageTitle = "Register";
require_once ROOT_PATH . '/includes/validation.php';
require_once ROOT_PATH . '/views/includes/header.php';

if ($auth->isLoggedIn()) {
    redirect('/dashboard');
}

$errors = [];
$formData = [
    'username' => '',
    'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once ROOT_PATH . '/includes/auth.php';
    $auth = new Auth($conn);

    $formData['username'] = sanitize_input($_POST['username']);
    $formData['email'] = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $usernameError = Validation::validateUsername($formData['username']);
    if ($usernameError) {
        $errors['username'] = $usernameError;
    }

    $emailError = Validation::validateEmail($formData['email']);
    if ($emailError) {
        $errors['email'] = $emailError;
    }

    $passwordError = Validation::validatePassword($password, $confirm_password);
    if ($passwordError) {
        $errors['password'] = $passwordError;
    }

    if (empty($errors)) {
        $result = $auth->register($formData['username'], $formData['email'], $password);
        if ($result['success']) {
            $_SESSION['registration_success'] = true;
            redirect('/login');
        } else {
            $errors['error'] = $result['message'];
        }
    }
}
?>

<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
                    <div class="col-lg-6">
                        <div class="text-center">
                            <h2>
                                <strong>Event Management System</strong>
                            </h2>
                        </div>
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <h3 class="text-center font-weight-light my-4">
                                    Register
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($errors['error'])): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $errors['error']; ?>
                                    </div>
                                <?php endif; ?>

                                <form id="registrationForm" method="POST" action="" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="form-floating mb-3 mb-md-0">
                                                <input class="form-control  <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" type="text" placeholder="Enter your first name" value="<?php echo htmlspecialchars($formData['username']); ?>" required />
                                                <label for="username">Username</label>

                                                <?php if (isset($errors['username'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <div class="form-floating">
                                                <input class="form-control  <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" type="email" name="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($formData['email']); ?>"
                                                    required />
                                                <label for="email">Email address</label>

                                                <?php if (isset($errors['email'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 mb-md-0">
                                                <input class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" name="password" id="password" type="password" placeholder="Create a password" />
                                                <label for="password">Password</label>

                                                <?php if (isset($errors['password'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3 mb-md-0">
                                                <input class="form-control" name="confirm_password" id="confirm_password" type="password" placeholder="Confirm password" />
                                                <label for="confirm_password">Confirm Password</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 mb-0">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                Register
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-3">
                                <div class="small"><a href="<?php echo BASE_URL; ?>/login">Have an account? Go to login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registrationForm');

        form.addEventListener('submit', function(event) {
            let isValid = true;

            const fields = [{
                    input: form.username,
                    validator: validateUsername
                },
                {
                    input: form.email,
                    validator: validateEmail
                },
                {
                    input: form.password,
                    validator: (value) => validatePassword(value, form.confirm_password.value)
                }
            ];

            fields.forEach(({
                input,
                validator
            }) => {
                const error = validator(input.value);
                if (error) {
                    showError(input, error);
                    isValid = false;
                } else {
                    clearError(input);
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });

        function validateUsername(username) {
            if (!username) return "Username is required";
            if (username.length < 3 || username.length > 50) return "Username must be between 3 and 50 characters";
            if (!/^[a-zA-Z0-9_]+$/.test(username)) return "Username can only contain letters, numbers, and underscores";
            return null;
        }

        function validateEmail(email) {
            if (!email) return "Email is required";
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) return "Invalid email format";
            return null;
        }

        function validatePassword(password, confirmPassword) {
            if (!password) return "Password is required";
            if (password.length < 8) return "Password must be at least 8 characters long";
            if (!confirmPassword) return "Confirm password is required";
            if (password !== confirmPassword) return "Passwords do not match";
            return null;
        }

        function showError(input, message) {
            input.classList.add('is-invalid');
            let errorDiv = input.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                input.parentNode.insertBefore(errorDiv, input.nextSibling);
            }
            errorDiv.textContent = message;
        }

        function clearError(input) {
            input.classList.remove('is-invalid');
            const errorDiv = input.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                errorDiv.remove();
            }
        }
    });
</script>
<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>
