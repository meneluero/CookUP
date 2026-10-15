<?php $isRegister = $mode === 'register'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
</head>
<body>
    <header>
        <a href="/"><strong>CookUP+</strong></a>
        <p>Core 2.4 • 48kHz / 24-bit lossless</p>
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

            <form action="<?= $isRegister ? '/register' : '/login' ?>" method="post">
                <?php if ($isRegister): ?>
                    <label for="handle">Producer handle</label>
                    <input type="text" id="handle" name="handle" placeholder="@your_name" required>
                <?php endif; ?>

                <label for="email">Producer email</label>
                <input type="email" id="email" name="email" placeholder="producer@cookup.audio" autocomplete="email" required>

                <label for="password">Studio key</label>
                <input type="password" id="password" name="password" autocomplete="<?= $isRegister ? 'new-password' : 'current-password' ?>" required>
                <?php if (!$isRegister): ?>
                    <a href="#">Forgot key?</a>
                <?php endif; ?>

                <?php if ($isRegister): ?>
                    <label for="password-repeat">Repeat studio key</label>
                    <input type="password" id="password-repeat" name="password_repeat" autocomplete="new-password" required>
                <?php endif; ?>

                <label>
                    <input type="checkbox" name="newsletter" value="1">
                    Send me curated stem packs &amp; weekly audio engineering drops.
                </label>

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
