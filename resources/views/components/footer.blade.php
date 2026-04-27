<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <span class="logo-icon" style="color: var(--accent); font-size:16px;">◈</span>
            <span style="font-family: var(--font-head); font-weight: 700; font-size: 14px;">MoodFlow</span>
        </div>
        <p class="footer-copy">
            &copy; {{ date('Y') }} MoodFlow &mdash; Kenali polamu, kendalikan harimu.
        </p>
        <div class="footer-badges">
            <span class="badge badge-accent">Laravel MVC</span>
            <span class="badge badge-accent">Blade Engine</span>
        </div>
    </div>
</footer>

<style>
.footer {
    border-top: 1px solid var(--border);
    margin-top: 48px;
    padding: 20px;
    position: relative;
    z-index: 1;
}

.footer-inner {
    max-width: 1100px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}

.footer-copy {
    flex: 1;
    font-size: 12px;
    color: var(--muted);
}

.footer-badges {
    display: flex;
    gap: 6px;
}

@media (max-width: 600px) {
    .footer-inner { flex-direction: column; align-items: flex-start; gap: 10px; }
    .footer-copy { flex: unset; }
}
</style>
