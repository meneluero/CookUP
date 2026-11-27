<?php $isRegister = $mode === 'register'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php require __DIR__ . '/partials/head.html.php'; ?>
</head>
<body>
    <header>
        <a href="/"><strong>CookUP+</strong></a>
        <p>Core 2.4 • 48kHz / 24-bit lossless</p>
        <?php require __DIR__ . '/partials/nav.html.php'; ?>
    </header>

    <main>
        <section aria-labelledby="intro-heading">
            <h1 id="intro-heading">Create together. Loop by loop.</h1>
            <p>High-density workspace for sample architects, mix engineers, and sound designers. Cloud session parity with &lt;4ms jitter.</p>
        </section>

        <section aria-labelledby="auth-heading">
            <p>Studio suite // Access level 0</p>
            <h2 id="auth-heading"><?= $isRegister ? 'Join the Studio' : 'Enter the Studio' ?></h2>
            <p>Cloud-synced stems, session stems, and zero-latency libraries.</p>

            <nav aria-label="Account">
                <a href="/login"<?= $isRegister ? '' : ' aria-current="page"' ?>>Sign in</a>
                <a href="/register"<?= $isRegister ? ' aria-current="page"' : '' ?>>Register</a>
            </nav>

            <?php if ($notice !== null): ?>
                <p role="status"><?= htmlspecialchars($notice) ?></p>
            <?php endif; ?>

            <?php if ($errors !== []): ?>
                <div role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= $isRegister ? '/register' : '/login' ?>" method="post">
                <input type="hidden" name="csrfToken" value="<?= htmlspecialchars($csrfToken) ?>">

                <?php if ($isRegister): ?>
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($form['name'] ?? '') ?>" autocomplete="name" maxlength="120" required>
                <?php endif; ?>

                <label for="email">Producer email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($form['email'] ?? '') ?>" placeholder="producer@cookup.audio" autocomplete="email" required>

                <label for="password">Studio key</label>
                <input type="password" id="password" name="password" autocomplete="<?= $isRegister ? 'new-password' : 'current-password' ?>"<?= $isRegister ? ' minlength="8"' : '' ?> required>
                <?php if (!$isRegister): ?>
                    <a href="#">Forgot key?</a>
                <?php endif; ?>

                <?php if ($isRegister): ?>
                    <label for="password-confirmation">Repeat studio key</label>
                    <input type="password" id="password-confirmation" name="passwordConfirmation" autocomplete="new-password" minlength="8" required>

                    <label>
                        <input type="checkbox" name="newsletter" value="1"<?= !empty($form['newsletter']) ? ' checked' : '' ?>>
                        Send me a weekly email with new loops.
                    </label>
                <?php endif; ?>

                <button type="submit"><?= $isRegister ? 'Create account' : 'Launch studio session' ?></button>
            </form>

            <p>Or continue with</p>
            <button type="button">Continue with Apple</button>
            <button type="button">Connect hardware studio key</button>
        </section>
    </main>

    <footer>
        <p><small>By entering CookUP+, you agree to the <a href="#">Studio Terms of Service</a> and <a href="#">Privacy Protocol</a>.</small></p>
    </footer>
</body>
</html>
