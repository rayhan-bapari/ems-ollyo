<?php
$pageTitle = "Login";
require_once ROOT_PATH . '/views/includes/header.php';
require_once ROOT_PATH . '/includes/validation.php';

if ($auth->isLoggedIn()) {
    redirect('/dashboard');
}

$registrationSuccess = isset($_SESSION['registration_success']) ? $_SESSION['registration_success'] : false;
unset($_SESSION['registration_success']);

$errors = [];
$formData = [
    'email' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once ROOT_PATH . '/includes/auth.php';
    $auth = new Auth($conn);

    $formData['email'] = sanitize_input($_POST['email']);
    $password = $_POST['password'];

    $errors = Validation::validateLogin($formData['email'], $password);

    if (empty($errors)) {
        $result = $auth->login($formData['email'], $password);

        if ($result['success']) {
            redirect('/dashboard');
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
                                <h3 class="text-center font-weight-light my-4">Login</h3>
                            </div>
                            <div class="card-body">
                                <?php if ($registrationSuccess): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        Registration successful! You can now login.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($errors['error'])): ?>
                                    <div class="alert alert-danger"><?php echo $errors['error']; ?></div>
                                <?php endif; ?>
                                <form id="loginForm" method="POST" action="" novalidate>
                                    <div class="form-floating mb-3">
                                        <input class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" name="email" id="email" type="email" placeholder="name@example.com" value="<?php echo htmlspecialchars($formData['email']); ?>"
                                            required />
                                        <label for="email">Email address</label>

                                        <?php if (isset($errors['email'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" name="password" id="password" type="password" placeholder="Password" required />
                                        <label for="password">Password</label>

                                        <?php if (isset($errors['password'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-4 mb-0">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-3">
                                <div class="small">
                                    <a href="<?php echo BASE_URL; ?>/register">Need an account? Sign up!</a>
                                </div>
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
        const form = document.getElementById('loginForm');

        form.addEventListener('submit', function(event) {
            let isValid = true;

            const fields = [{
                    input: form.email,
                    validator: validateEmail
                },
                {
                    input: form.password,
                    validator: validatePassword
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

        function validateEmail(email) {
            if (!email) return "Email is required";
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) return "Invalid email format";
            return null;
        }

        function validatePassword(password) {
            if (!password) return "Password is required";
            if (password.length < 8) return "Password must be at least 8 characters long";
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
