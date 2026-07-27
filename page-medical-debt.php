<?php
/**
 * Template Name: Medical Debt Relief Landing
 * Landing page for the Lewis County Democrats medical debt relief project.
 *
 * Set a "donate_url" custom field on the page to point the donate buttons
 * at the real donation platform. Until then they fall back to the #donate anchor.
 *
 * Optional per-amount custom fields for pre-filled donation links, used by the
 * "See How Far Your Donation Can Go" tiles (each falls back to donate_url):
 *   donate_url_10, donate_url_25, donate_url_50, donate_url_100
 *
 * @package LCD_Theme
 */

get_header();

$donate_url = get_post_meta(get_the_ID(), 'donate_url', true);
$donate_url = $donate_url ? esc_url($donate_url) : '#donate';
$story_url  = 'https://dems.work/tell-my-story';

// Pre-filled amount links, falling back to the general donate URL.
$donate_amounts = array();
foreach (array(10, 25, 50, 100) as $amount) {
    $amount_url = get_post_meta(get_the_ID(), 'donate_url_' . $amount, true);
    $donate_amounts[$amount] = $amount_url ? esc_url($amount_url) : $donate_url;
}
?>

<main id="primary" class="site-main mdr-page">

    <!-- ============ HERO ============ -->
    <section class="mdr-hero">
        <div class="mdr-hero-bg" aria-hidden="true"></div>
        <div class="mdr-container mdr-hero-inner">
            <p class="mdr-hero-kicker"><?php esc_html_e('A Lewis County Democrats Project', 'lcd-theme'); ?></p>
            <h1 class="mdr-hero-title"><?php esc_html_e('Help Us Wipe Out Medical Debt in Lewis County', 'lcd-theme'); ?></h1>
            <p class="mdr-hero-lead">
                <?php esc_html_e('For every $1 we raise, up to $200 in qualifying medical debt can be forgiven for our neighbors here in Lewis County and across Washington.', 'lcd-theme'); ?>
            </p>
            <p class="mdr-hero-sub">
                <?php esc_html_e('The Lewis County Democrats are leading a first-of-its-kind effort in Washington to turn small donations into life-changing relief, with support from Dems Work and Contest Every Race.', 'lcd-theme'); ?>
            </p>
            <div class="mdr-hero-cta">
                <a href="<?php echo $donate_url; ?>" class="mdr-button mdr-button-accent mdr-button-xl"><?php esc_html_e('Help Erase Medical Debt', 'lcd-theme'); ?></a>
                <p class="mdr-cta-note"><?php esc_html_e('Every $1 donated can forgive up to $200 in qualifying debt.', 'lcd-theme'); ?></p>
            </div>
        </div>
    </section>

    <!-- ============ GIANT MULTIPLIER / HOW IT WORKS ============ -->
    <section class="mdr-multiplier">
        <div class="mdr-container">
            <div class="mdr-multiplier-visual" role="img" aria-label="<?php esc_attr_e('One dollar becomes up to two hundred dollars of relief', 'lcd-theme'); ?>">
                <span class="mdr-multiplier-from">$1</span>
                <span class="mdr-multiplier-arrow" aria-hidden="true">&rarr;</span>
                <span class="mdr-multiplier-to">up to <strong>$200</strong></span>
            </div>
            <div class="mdr-multiplier-explain">
                <p><?php esc_html_e('How? Medical debt is often sold for pennies on the dollar after providers have been unable to collect it. That creates an unusual opportunity: instead of buying that debt to collect it, it can be purchased to forgive it.', 'lcd-theme'); ?></p>
            </div>
            <p class="mdr-multiplier-caption"><?php esc_html_e("We aren't paying medical debt. We're making it disappear.", 'lcd-theme'); ?></p>
        </div>
    </section>

    <!-- ============ IMPACT CARDS ============ -->
    <section class="mdr-impact">
        <div class="mdr-container">
            <div class="mdr-card-grid">
                <div class="mdr-card">
                    <div class="mdr-card-figure"><?php esc_html_e('100% No Strings Attached', 'lcd-theme'); ?></div>
                    <p><?php esc_html_e("People whose debt is forgiven don't apply, don't owe us anything, and don't have to pay it back.", 'lcd-theme'); ?></p>
                </div>
                <div class="mdr-card">
                    <div class="mdr-card-figure"><?php esc_html_e('Right Here at Home', 'lcd-theme'); ?></div>
                    <p><?php esc_html_e('Money raised through this campaign is guaranteed to relieve qualifying medical debt in Washington State, with our Lewis County neighbors prioritized.', 'lcd-theme'); ?></p>
                </div>
            </div>
            <div class="mdr-impact-cta">
                <a href="<?php echo $donate_url; ?>" class="mdr-button mdr-button-accent"><?php esc_html_e('Turn $10 Into Up To $2,000 of Relief', 'lcd-theme'); ?></a>
            </div>
        </div>
    </section>

    <!-- ============ REAL RELIEF / NO CATCH ============ -->
    <section class="mdr-section mdr-relief">
        <div class="mdr-container mdr-two-col">
            <div class="mdr-col-text">
                <h2><?php esc_html_e('Real Relief. No Catch.', 'lcd-theme'); ?></h2>
                <p><?php esc_html_e('Medical debt can follow someone for years after the illness, injury, or emergency that created it.', 'lcd-theme'); ?></p>
                <p><strong><?php esc_html_e("We're working to make some of that burden simply disappear.", 'lcd-theme'); ?></strong></p>
                <p><?php esc_html_e('Through this project, qualifying medical debt is purchased and permanently forgiven. The person receiving relief pays nothing. There are no strings attached, no repayment and no tax bill from the forgiven debt.', 'lcd-theme'); ?></p>
                <ul class="mdr-checklist">
                    <li><?php esc_html_e("They don't have to join anything.", 'lcd-theme'); ?></li>
                    <li><?php esc_html_e("They don't have to support a political party.", 'lcd-theme'); ?></li>
                    <li><?php esc_html_e("They don't even have to ask for help.", 'lcd-theme'); ?></li>
                </ul>
                <p class="mdr-standalone"><?php esc_html_e('Their debt is simply gone.', 'lcd-theme'); ?></p>
            </div>
            <div class="mdr-col-media">
                <img class="mdr-photo" src="https://lewiscountydemocrats.org/wp-content/uploads/2026/07/shutterstock_83597350-1-1.jpg" alt="<?php esc_attr_e('Green road sign reading Debt Relief Just Ahead against a bright sky', 'lcd-theme'); ?>" loading="lazy">
            </div>
        </div>
    </section>

    <!-- ============ WHY LCD ============ -->
    <section class="mdr-section mdr-why">
        <div class="mdr-container mdr-two-col mdr-two-col-reverse">
            <div class="mdr-col-media">
                <img class="mdr-photo" src="https://lewiscountydemocrats.org/wp-content/uploads/2026/07/helping-hand-icon-symbol-of-support-rescue-and-assistance-two-hands-holding-each-other-icon-for-charity-and-teamwork-vector.jpg" alt="<?php esc_attr_e('Two hands holding each other, a symbol of support and assistance', 'lcd-theme'); ?>" loading="lazy">
            </div>
            <div class="mdr-col-text">
                <h2><?php esc_html_e('Politics Should Be About Helping People', 'lcd-theme'); ?></h2>
                <p><?php esc_html_e('We believe a local political party should do more than ask for votes.', 'lcd-theme'); ?></p>
                <p><strong><?php esc_html_e('It should be part of the community it hopes to serve.', 'lcd-theme'); ?></strong></p>
                <p><?php esc_html_e("You shouldn't go into debt for getting sick. But in America, that outcome is far too familiar. That's why the Lewis County Democrats are taking the lead on this project: raising money to relieve medical debt for our neighbors while shining a light on a healthcare system that leaves too many working families owing thousands of dollars simply because someone got sick.", 'lcd-theme'); ?></p>
                <p><?php esc_html_e("Together, we can help families today while building the momentum to fix the system for good. That's how we help our neighbors, create lasting change, and ensure that getting sick no longer means falling into debt.", 'lcd-theme'); ?></p>
            </div>
        </div>
    </section>

    <!-- ============ LEADING THE WAY ============ -->
    <section class="mdr-section mdr-leading">
        <div class="mdr-container mdr-narrow mdr-center">
            <h2><?php esc_html_e('Lewis County Is Leading the Way', 'lcd-theme'); ?></h2>
            <p><?php esc_html_e('Local Democratic organizations around the country are participating in this effort.', 'lcd-theme'); ?></p>
            <p><?php esc_html_e('Here in Washington, the Lewis County Democrats are the first and only local Democratic Party organization participating.', 'lcd-theme'); ?></p>
            <p><?php esc_html_e("We're proud that a rural county is leading the way. We're even prouder that the people who benefit won't be chosen based on politics.", 'lcd-theme'); ?></p>
            <blockquote class="mdr-pullquote">
                <?php esc_html_e("Medical debt doesn't care how you vote. Neither does this project.", 'lcd-theme'); ?>
            </blockquote>
            <p><?php esc_html_e("Republican, Democrat, independent or none of the above: if your qualifying debt is selected for relief, it's forgiven. Period.", 'lcd-theme'); ?></p>
        </div>
    </section>

    <!-- ============ DONATION MULTIPLIER / DONATE ============ -->
    <section class="mdr-section mdr-donate" id="donate">
        <div class="mdr-container">
            <h2 class="mdr-center"><?php esc_html_e('See How Far Your Donation Can Go', 'lcd-theme'); ?></h2>
            <p class="mdr-center mdr-donate-lead">
                <?php esc_html_e("A $10 contribution to a normal fundraiser is $10. Here, that same $10 can mean up to $2,000 of medical debt erased for our neighbors. Your donation doesn't make a payment toward someone's bill.", 'lcd-theme'); ?>
                <strong><?php esc_html_e('It can make the bill disappear.', 'lcd-theme'); ?></strong>
            </p>
            <div class="mdr-donate-grid">
                <a href="<?php echo $donate_amounts[10]; ?>" class="mdr-donate-tile">
                    <span class="mdr-donate-give">$10</span>
                    <span class="mdr-donate-get"><?php esc_html_e('up to $2,000 erased', 'lcd-theme'); ?></span>
                </a>
                <a href="<?php echo $donate_amounts[25]; ?>" class="mdr-donate-tile">
                    <span class="mdr-donate-give">$25</span>
                    <span class="mdr-donate-get"><?php esc_html_e('up to $5,000 erased', 'lcd-theme'); ?></span>
                </a>
                <a href="<?php echo $donate_amounts[50]; ?>" class="mdr-donate-tile">
                    <span class="mdr-donate-give">$50</span>
                    <span class="mdr-donate-get"><?php esc_html_e('up to $10,000 erased', 'lcd-theme'); ?></span>
                </a>
                <a href="<?php echo $donate_amounts[100]; ?>" class="mdr-donate-tile">
                    <span class="mdr-donate-give">$100</span>
                    <span class="mdr-donate-get"><?php esc_html_e('up to $20,000 erased', 'lcd-theme'); ?></span>
                </a>
            </div>
            <div class="mdr-donate-cta">
                <a href="<?php echo $donate_url; ?>" class="mdr-button mdr-button-accent mdr-button-xl"><?php esc_html_e('Help Erase Medical Debt', 'lcd-theme'); ?></a>
                <p class="mdr-cta-note"><?php esc_html_e('No application. No repayment. No strings.', 'lcd-theme'); ?></p>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="mdr-section mdr-faq">
        <div class="mdr-container mdr-narrow">
            <h2 class="mdr-center"><?php esc_html_e('Questions? Good. Here Are Answers.', 'lcd-theme'); ?></h2>

            <details class="mdr-faq-item">
                <summary><?php esc_html_e('Who receives the relief?', 'lcd-theme'); ?></summary>
                <p><?php esc_html_e("Qualifying medical debt belonging to Washington residents is identified through the program, and debt belonging to our Lewis County neighbors is prioritized. Individuals don't need to apply or ask for assistance.", 'lcd-theme'); ?></p>
            </details>

            <details class="mdr-faq-item">
                <summary><?php esc_html_e('Does my donation stay in Lewis County?', 'lcd-theme'); ?></summary>
                <p><?php esc_html_e('Every dollar raised through this campaign is guaranteed to relieve qualifying medical debt in Washington State. Debt belonging to Lewis County residents is prioritized as it becomes available for relief.', 'lcd-theme'); ?></p>
            </details>

            <details class="mdr-faq-item">
                <summary><?php esc_html_e('Does someone have to be a Democrat?', 'lcd-theme'); ?></summary>
                <p><?php esc_html_e('Absolutely not. Political affiliation plays no role in who receives relief.', 'lcd-theme'); ?></p>
            </details>

            <details class="mdr-faq-item">
                <summary><?php esc_html_e('Does the recipient owe anything afterward?', 'lcd-theme'); ?></summary>
                <p><?php esc_html_e('No. The debt is forgiven completely. There is no repayment, obligation or catch.', 'lcd-theme'); ?></p>
            </details>

            <details class="mdr-faq-item">
                <summary><?php esc_html_e('Will someone owe taxes on the forgiven debt?', 'lcd-theme'); ?></summary>
                <p><?php esc_html_e('No. Recipients do not receive a tax bill because their debt was relieved through this program.', 'lcd-theme'); ?></p>
            </details>

            <details class="mdr-faq-item">
                <summary><?php esc_html_e('Why can my donation have such a large impact?', 'lcd-theme'); ?></summary>
                <p><?php esc_html_e('Medical debt can often be acquired for a tiny fraction of its face value. Instead of acquiring the debt to collect it, this project acquires qualifying debt so it can be permanently forgiven.', 'lcd-theme'); ?></p>
            </details>
        </div>
    </section>

    <!-- ============ FINAL CTA ============ -->
    <section class="mdr-final-cta">
        <div class="mdr-container mdr-center">
            <p class="mdr-final-line"><?php esc_html_e('Imagine opening a letter expecting another medical bill, and learning instead that the debt is gone.', 'lcd-theme'); ?></p>
            <h2><?php esc_html_e("We can't erase every medical bill. Together, we can erase a lot of them.", 'lcd-theme'); ?></h2>
            <a href="<?php echo $donate_url; ?>" class="mdr-button mdr-button-accent mdr-button-xl"><?php esc_html_e('Help a Neighbor Start Over', 'lcd-theme'); ?></a>
            <p class="mdr-cta-note"><?php esc_html_e("Your $10 could erase someone's $2,000 medical bill.", 'lcd-theme'); ?></p>
        </div>
    </section>

    <!-- ============ STORY COLLECTION (SECONDARY) ============ -->
    <section class="mdr-section mdr-stories">
        <div class="mdr-container mdr-narrow mdr-center">
            <h2><?php esc_html_e('Medical Debt Has a Story, Too', 'lcd-theme'); ?></h2>
            <p><?php esc_html_e("We're also collecting stories from Lewis County residents about how healthcare costs and medical debt have affected their families. Sharing is completely optional, but your experience can help show why this work matters.", 'lcd-theme'); ?></p>
            <a href="<?php echo esc_url($story_url); ?>" class="mdr-button mdr-button-outline" target="_blank" rel="noopener"><?php esc_html_e('Share Your Story', 'lcd-theme'); ?></a>
        </div>
    </section>

    <!-- ============ PARTNERSHIP FOOTER ============ -->
    <section class="mdr-partners">
        <div class="mdr-container mdr-center">
            <p class="mdr-partners-lead">
                <strong><?php esc_html_e('Led by the Lewis County Democrats', 'lcd-theme'); ?></strong><br>
                <?php esc_html_e('In partnership with', 'lcd-theme'); ?>
                <a href="https://dems.work/" target="_blank" rel="noopener"><?php esc_html_e('Dems Work', 'lcd-theme'); ?></a>
                <?php esc_html_e('and', 'lcd-theme'); ?>
                <a href="https://www.contesteveryrace.com/" target="_blank" rel="noopener"><?php esc_html_e('Contest Every Race', 'lcd-theme'); ?></a>.
            </p>
            <div class="mdr-partner-logos">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="https://lewiscountydemocrats.org/wp-content/uploads/2024/12/LCD-Shape-Logo.png" alt="<?php esc_attr_e('Lewis County Democrats', 'lcd-theme'); ?>" loading="lazy">
                </a>
                <a href="https://dems.work/" target="_blank" rel="noopener">
                    <img src="https://lewiscountydemocrats.org/wp-content/uploads/2026/07/Screenshot-2026-07-14-at-9.34.08-AM_20260714133423334329-1.png" alt="<?php esc_attr_e('Democrats Work, a Contest Every Race program', 'lcd-theme'); ?>" loading="lazy">
                </a>
                <a href="https://www.contesteveryrace.com/" target="_blank" rel="noopener">
                    <img src="https://lewiscountydemocrats.org/wp-content/uploads/2026/07/cer-logo.jpg" alt="<?php esc_attr_e('Contest Every Race', 'lcd-theme'); ?>" loading="lazy">
                </a>
            </div>
            <p class="mdr-partners-note">
                <a href="https://dems.work/" target="_blank" rel="noopener"><?php esc_html_e('Dems Work', 'lcd-theme'); ?></a>
                <?php esc_html_e('is a program of', 'lcd-theme'); ?>
                <a href="https://www.contesteveryrace.com/" target="_blank" rel="noopener"><?php esc_html_e('Contest Every Race', 'lcd-theme'); ?></a>,
                <?php esc_html_e('which supports local Democratic organizations around the country with funding and infrastructure for year-round community organizing.', 'lcd-theme'); ?>
            </p>
        </div>
    </section>

</main>

<?php
get_footer();
