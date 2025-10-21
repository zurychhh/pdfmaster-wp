<?php
/**
 * Template Name: Homepage P1 Template
 * Description: Custom homepage with P1 visual enhancements (sticky bar, enhanced hovers, pricing comparison, FAQ accordion)
 *
 * @package PDFMaster
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="pdfm-homepage-p1">

    <!-- Sticky Trust Bar -->
    <section class="pdfm-trust-bar" id="trust-bar">
        <div class="container">
            <div class="trust-content">
                <div class="trust-item stars">
                    <svg class="star-icon" width="20" height="20" fill="#f59e0b" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star-icon" width="20" height="20" fill="#f59e0b" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star-icon" width="20" height="20" fill="#f59e0b" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star-icon" width="20" height="20" fill="#f59e0b" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="star-icon" width="20" height="20" fill="#f59e0b" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>4.9/5</span>
                </div>
                <span class="separator">•</span>
                <div class="trust-item"><span>2M+ users</span></div>
                <span class="separator">•</span>
                <div class="trust-item"><span>No signup</span></div>
                <span class="separator">•</span>
                <div class="trust-item"><span>Files deleted after 1h</span></div>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="pdfm-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Professional PDF Tools in 30 Seconds</h1>
                <p class="hero-subtitle">Compress, merge, split and convert PDF files without installing software. Just $0.99 per action. No subscriptions, no hidden fees.</p>

                <div class="hero-ctas">
                    <a href="<?php echo esc_url(home_url('/test-processor/')); ?>" class="btn btn-primary">Try Any Tool — $0.99</a>
                    <a href="#how-it-works" class="btn btn-secondary">See How It Works</a>
                </div>

                <div class="trust-indicators">
                    <div class="trust-badge">
                        <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>No signup required</span>
                    </div>
                    <div class="trust-badge">
                        <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Files deleted after 1 hour</span>
                    </div>
                    <div class="trust-badge">
                        <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>Bank-level encryption</span>
                    </div>
                    <div class="trust-badge">
                        <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>2M+ users monthly</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges Section -->
    <section class="pdfm-trust-badges">
        <div class="container">
            <div class="badges-grid">
                <div class="badge-card">
                    <div class="badge-icon bg-blue">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Secure Payments</h3>
                    <p>We never see your card details. Payments processed by Stripe (PCI DSS Level 1 certified).</p>
                </div>

                <div class="badge-card">
                    <div class="badge-icon bg-green">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Data Protection</h3>
                    <p>256-bit AES encryption during transfer. Files auto-deleted after 60 minutes—no exceptions, no logs.</p>
                </div>

                <div class="badge-card">
                    <div class="badge-icon bg-purple">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>No Tracking, No Ads</h3>
                    <p>We don't sell your data. We don't track your usage. You pay, you convert, you leave.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tools Showcase -->
    <section class="pdfm-tools" id="tools">
        <div class="container">
            <div class="section-header">
                <h2>All Tools, One Simple Price</h2>
                <p>$0.99 per action. No subscriptions, no packages, no complexity.</p>
            </div>

            <div class="tools-grid">
                <div class="tool-card">
                    <div class="tool-icon bg-blue">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Compress PDF</h3>
                    <p>Reduce file size by up to 90% without quality loss. Perfect for email attachments.</p>
                    <div class="tool-price">$0.99</div>
                    <p class="tool-time">~8 seconds processing</p>
                </div>

                <div class="tool-card">
                    <div class="tool-icon bg-green">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Merge PDFs</h3>
                    <p>Combine multiple PDF files into one organized document. Up to 20 files at once.</p>
                    <div class="tool-price">$0.99</div>
                    <p class="tool-time">~8 seconds processing</p>
                </div>

                <div class="tool-card">
                    <div class="tool-icon bg-purple">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Split PDF</h3>
                    <p>Extract specific pages or split into separate files. Simple page range selection.</p>
                    <div class="tool-price">$0.99</div>
                    <p class="tool-time">~8 seconds processing</p>
                </div>

                <div class="tool-card">
                    <div class="tool-icon bg-orange">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Convert to PDF</h3>
                    <p>Convert Word, Excel, PowerPoint and images to PDF. Quality options available.</p>
                    <div class="tool-price">$0.99</div>
                    <p class="tool-time">~8 seconds processing</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Comparison -->
    <section class="pdfm-pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <h2>Simple, Honest Pricing</h2>
                <p>One price for everything. No tiers, no packages, no confusion.</p>
            </div>

            <div class="pricing-grid">
                <div class="pricing-card competitor">
                    <div class="badge badge-red">Typical Competitor</div>
                    <div class="pricing-icon">
                        <svg width="48" height="48" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="price">$108</div>
                    <div class="price-period">per year subscription</div>

                    <ul class="features-list">
                        <li class="feature-bad">
                            <svg class="x-icon" width="20" height="20" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span>Most users need only 9 actions/year</span>
                        </li>
                        <li class="feature-bad">
                            <svg class="x-icon" width="20" height="20" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span>Paying for unused features</span>
                        </li>
                        <li class="feature-bad">
                            <svg class="x-icon" width="20" height="20" fill="#ef4444" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span>Auto-renewal trap</span>
                        </li>
                    </ul>

                    <div class="wasted">= $99.09 wasted annually</div>
                </div>

                <div class="pricing-card pdfmaster">
                    <div class="badge badge-green">PDFMaster ⭐ Best Value</div>
                    <div class="pricing-icon">
                        <svg width="48" height="48" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="price">$0.99</div>
                    <div class="price-period">per action</div>

                    <ul class="features-list">
                        <li class="feature-good">
                            <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Any tool: Compress, Merge, Split, Convert</span>
                        </li>
                        <li class="feature-good">
                            <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Files up to 100MB</span>
                        </li>
                        <li class="feature-good">
                            <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>No signup required</span>
                        </li>
                        <li class="feature-good">
                            <svg class="check-icon" width="20" height="20" fill="#10b981" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>No subscription, no recurring charges</span>
                        </li>
                    </ul>

                    <div class="calculation-box">
                        <div class="calc-small">9 actions × $0.99 each</div>
                        <div class="calc-big">= $8.91 total per year</div>
                    </div>

                    <a href="<?php echo esc_url(home_url('/test-processor/')); ?>" class="btn btn-primary btn-full">Try Any Tool Now</a>
                </div>
            </div>

            <div class="savings-callout">
                <div class="savings-amount">Save $99.09 annually</div>
                <div class="savings-text">by paying only for what you actually use</div>
            </div>
        </div>
    </section>

    <!-- Why It Works -->
    <section class="pdfm-why" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>Why it works?</h2>
            </div>

            <div class="why-grid">
                <div class="why-card">
                    <div class="why-icon bg-blue">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Pay Only for What You Need</h3>
                    <p>Unlike $10+/month subscriptions, you pay $0.99 only when you convert, merge, or compress a file.</p>
                </div>

                <div class="why-card">
                    <div class="why-icon bg-green">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Zero Commitment, Zero Hassle</h3>
                    <p>No account creation. No credit card on file. Just upload, pay once via Stripe, and download.</p>
                </div>

                <div class="why-card">
                    <div class="why-icon bg-purple">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Military-Grade Security</h3>
                    <p>Files encrypted during upload, processing, and download. Permanently deleted within 1 hour.</p>
                </div>

                <div class="why-card">
                    <div class="why-icon bg-orange">
                        <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3>Lightning-Fast Processing</h3>
                    <p>Most operations complete in under 10 seconds. No waiting rooms, no throttling—instant results.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Accordion -->
    <section class="pdfm-faq" id="faq">
        <div class="container">
            <div class="section-header">
                <h2>Frequently Asked Questions</h2>
            </div>

            <div class="faq-list">
                <div class="faq-item active">
                    <button class="faq-question">
                        <span>Why $0.99 per use instead of a subscription?</span>
                        <svg class="chevron" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="faq-answer">
                        <p>Because most people process PDFs only 2-5 times per month—not 50. Why pay $10-20/month for something you barely use? With us, you pay $0.99 only when you need it. If you use it 10 times a year, that's $9.90 total instead of $120-240 for annual subscriptions.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Do I need to create an account?</span>
                        <svg class="chevron" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="faq-answer">
                        <p>Nope. Just upload your file, pay, and download. We'll email you a receipt, but that's it. No passwords, no login, no profile.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>What payment methods do you accept?</span>
                        <svg class="chevron" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="faq-answer">
                        <p>We accept all major credit/debit cards via Stripe, plus PayPal and Google Pay.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Can I get a refund?</span>
                        <svg class="chevron" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="faq-answer">
                        <p>Yes. If your file fails to process or the output is corrupted, email us within 24 hours for a full refund—no questions asked.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>How long do you keep my files?</span>
                        <svg class="chevron" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="faq-answer">
                        <p>Maximum 1 hour. After that, they're permanently deleted from our servers. We don't store, share, or access your documents.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        <span>Is there a file size limit?</span>
                        <svg class="chevron" width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="faq-answer">
                        <p>Each file can be up to 100MB. Need larger? Email us for custom enterprise pricing.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="pdfm-cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Stop Wasting Money on Subscriptions?</h2>
                <p>Join 2M+ smart users who pay only for what they use. Process your first PDF in under 60 seconds.</p>

                <a href="<?php echo esc_url(home_url('/test-processor/')); ?>" class="btn btn-cta">Try Any Tool — $0.99</a>

                <div class="cta-trust">
                    <div class="trust-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        <span>Money-Back Guarantee</span>
                    </div>
                    <span class="separator">•</span>
                    <div class="trust-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>No Auto-Renewal</span>
                    </div>
                    <span class="separator">•</span>
                    <div class="trust-item">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>No Subscription Ever</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>
