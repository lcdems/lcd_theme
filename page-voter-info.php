<?php
/**
 * Template Name: Voter Information
 * The template for displaying voter information page
 *
 * @package LCD_Theme
 */

get_header();
?>

<main id="primary" class="site-main voter-info-template">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('page'); ?>>
            <header class="entry-header<?php echo has_post_thumbnail() ? ' has-featured-image' : ''; ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-header-content">
                    <?php if (!is_front_page()) : ?>
                        <div class="breadcrumbs">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'lcd-theme'); ?></a>
                            <span class="separator"> › </span>
                            <?php
                            if ($post->post_parent) {
                                $ancestors = get_post_ancestors($post->ID);
                                $ancestors = array_reverse($ancestors);
                                foreach ($ancestors as $ancestor) {
                                    $ancestor_post = get_post($ancestor);
                                    echo '<a href="' . get_permalink($ancestor) . '">' . esc_html($ancestor_post->post_title) . '</a>';
                                    echo '<span class="separator"> › </span>';
                                }
                            }
                            ?>
                            <span class="current"><?php the_title(); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </div>
            </header>

            <div class="content-wrapper<?php echo get_post_meta(get_the_ID(), 'full_width_content', true) ? ' full-width-content' : ''; ?>">
                <div class="entry-content">
                    <?php 
                    if (post_password_required()) {
                        get_template_part('template-parts/password-form');
                    } else {
                    ?>
        
        <div class="voter-info-container">
            <div class="quick-links">
                <a href="https://voter.votewa.gov/WhereToVote.aspx" class="quick-link-card" target="_blank">
                    <h3>🗳️ Track Your Ballot</h3>
                    <p>Check if your ballot was received and counted</p>
                </a>
                
                <a href="https://www.sos.wa.gov/elections/data-research/election-results-and-voters-pamphlets" class="quick-link-card" target="_blank">
                    <h3>📖 Voter's Guide</h3>
                    <p>Read candidate statements and learn about measures</p>
                </a>
                
                <a href="https://elections.lewiscountywa.gov/drop-box-locations/" class="quick-link-card" target="_blank">
                    <h3>📍 Drop Box Locations</h3>
                    <p>Find all 14 ballot drop boxes in Lewis County</p>
                </a>
                
                <a href="https://voter.votewa.gov/" class="quick-link-card" target="_blank">
                    <h3>✏️ Register to Vote</h3>
                    <p>Register online or update your information</p>
                </a>
            </div>
            
            <div class="voter-section">
                <h2>How Washington's Vote-By-Mail System Works</h2>
                <p>Washington has conducted all elections by mail since 2011. Lewis County started vote-by-mail in 2005. Every registered voter receives a ballot in the mail—no need to request one. Here's how the process works from start to finish.</p>
                
                <h3>The ballot journey</h3>
                
                <div class="process-step">
                    <h4><span class="step-number">1</span> Ballots are mailed</h4>
                    <p>County elections offices mail ballots to all registered voters <strong>at least 18 days before Election Day</strong>. For Lewis County, ballots typically arrive in mailboxes 3-5 days after they're mailed. Military and overseas voters receive ballots 45 days before Election Day.</p>
                </div>
                
                <div class="process-step">
                    <h4><span class="step-number">2</span> You receive and complete your ballot</h4>
                    <p>Your ballot packet includes:</p>
                    <ul>
                        <li>The ballot itself</li>
                        <li>A secrecy envelope (recommended but not required)</li>
                        <li>A signed oath envelope with prepaid postage</li>
                        <li>Instructions and voter information</li>
                    </ul>
                    <p>Read the candidates and measures carefully, mark your choices, and place your ballot in the secrecy envelope, then into the oath envelope. <strong>Sign the oath envelope</strong>—this is required for your vote to count.</p>
                </div>
                
                <div class="process-step">
                    <h4><span class="step-number">3</span> Return your ballot</h4>
                    <p>You have two options:</p>
                    <ul>
                        <li><strong>Drop box:</strong> Use any of Lewis County's 14 official ballot drop boxes. Available 18 days before the election, they lock at exactly 8:00 PM on Election Day. This is the fastest and most reliable option.</li>
                        <li><strong>Mail:</strong> No stamp needed (prepaid postage included). Must be postmarked by Election Day. Due to potential USPS delays, mail your ballot at least 7 days before Election Day.</li>
                    </ul>
                </div>
                
                <div class="important-box">
                    <strong>Important timing:</strong> Ballots received after Election Day can still be counted if they have a valid postmark of Election Day or earlier and arrive before the county certifies the election (21 days after Election Day).
                </div>
                
                <div class="process-step">
                    <h4><span class="step-number">4</span> Ballot is received and logged</h4>
                    <p>When your ballot arrives at the elections office:</p>
                    <ul>
                        <li>The barcode on your envelope is scanned</li>
                        <li>Your ballot status updates to "Received" in the VoteWA system</li>
                        <li>This prevents anyone from casting more than one ballot</li>
                        <li>Allow 3-5 business days for your status to update after dropping off or mailing</li>
                    </ul>
                </div>
                
                <div class="process-step">
                    <h4><span class="step-number">5</span> Signature verification</h4>
                    <p>Election workers trained by the Washington State Patrol compare your signature on the envelope to the signature in your voter registration file (usually from your driver's license).</p>
                    <p><strong>If signatures match:</strong> Your ballot status updates to "Accepted" and moves to the next step.</p>
                    <p><strong>If signatures don't match or are missing:</strong> Your ballot is "challenged" and you receive notification by mail, phone, email, or text explaining how to fix the issue. You have until the county certifies the election (21 days after Election Day) to resolve signature challenges.</p>
                </div>
                
                <div class="process-step">
                    <h4><span class="step-number">6</span> Opening and scanning</h4>
                    <p>Once your signature is verified, election workers:</p>
                    <ul>
                        <li>Open your ballot envelope</li>
                        <li>Separate your ballot from the signed envelope (preserving ballot secrecy)</li>
                        <li>Inspect the ballot to ensure it's filled out properly</li>
                        <li>Scan the ballot into the voting system</li>
                    </ul>
                    <p>Ballots are scanned throughout the 18-day voting period, but votes aren't counted until after 8 PM on Election Day.</p>
                </div>
                
                <div class="process-step">
                    <h4><span class="step-number">7</span> Tabulation and results</h4>
                    <p>At 8:00 PM on Election Day, tabulation begins. Since ballots are already scanned, initial results appear quickly. However:</p>
                    <ul>
                        <li>Ballots dropped off on Election Day are processed over subsequent days</li>
                        <li>Mailed ballots with valid postmarks continue arriving for days after the election</li>
                        <li>Counties post updated results daily (larger counties) or every three days (smaller counties)</li>
                        <li>Counties have 21 days to certify results</li>
                        <li>The Secretary of State certifies statewide results within 30 days of Election Day</li>
                    </ul>
                </div>
                
                <div class="info-box">
                    <strong>Why results change after Election Day:</strong> Washington accepts ballots postmarked by Election Day, even if received later. This means results can shift as late-arriving ballots are counted. This is normal and doesn't indicate fraud—it's how the system is designed to ensure every valid vote counts.
                </div>
            </div>
            
            <div class="voter-section">
                <h2>Security measures</h2>
                <p>Washington's election system includes multiple layers of security to protect ballot integrity.</p>
                
                <h3>Drop box security</h3>
                <ul>
                    <li>Built with quarter-inch steel and anchored to the ground</li>
                    <li>Equipped with fire suppression systems</li>
                    <li>Locked with red zip ties that show if tampered with</li>
                    <li>Collected daily by teams of two or more election workers</li>
                    <li>Some counties have law enforcement accompany collection teams</li>
                    <li>Surveillance and security protocols vary by county</li>
                </ul>
                
                <h3>Processing security</h3>
                <ul>
                    <li>Election workers check personal belongings at the counter before shifts</li>
                    <li>Workers wear color-coded identification lanyards</li>
                    <li>All work areas are monitored by security cameras</li>
                    <li>Voting systems are kept in locked rooms with sign-in/sign-out logs</li>
                    <li>Workers always operate in groups of two or more</li>
                    <li>Observers from political parties monitor the entire process</li>
                    <li>The public can tour ballot processing facilities or watch via webcam</li>
                </ul>
                
                <h3>System testing and audits</h3>
                <ul>
                    <li><strong>Pre-election testing:</strong> Before every election, counties test voting systems by scanning test ballots with known outcomes to verify accuracy</li>
                    <li><strong>Post-election audits:</strong> After Election Day, officials hand count randomly selected ballot batches and compare them to machine totals</li>
                    <li><strong>Risk-limiting audits:</strong> Washington conducts statewide statistical audits to confirm results</li>
                    <li><strong>Signature compliance checks:</strong> The Secretary of State verifies that signature verification standards were followed</li>
                </ul>
                
                <h3>Voter registration security</h3>
                <ul>
                    <li>Washington maintains a single statewide voter registration database (VoteWA)</li>
                    <li>Counties can track registration changes across the state in real-time</li>
                    <li>The system prevents duplicate voting attempts</li>
                    <li>Voter rolls are updated based on death records and change-of-address information</li>
                    <li>Washington participates in the Electronic Registration Information Center (ERIC), which checks for duplicate registrations across states</li>
                </ul>
                
                <h3>Legal consequences for fraud</h3>
                <p>Voter fraud is a class C felony in Washington, carrying substantial criminal penalties and prison time. Both Republican and Democratic party observers monitor the ballot counting process and can file challenges for suspicious or potentially illegal ballots.</p>
            </div>
            
            <div class="voter-section">
                <h2>Common questions</h2>
                
                <h3>Should I use a drop box or mail my ballot?</h3>
                <p>Both methods are secure, but drop boxes eliminate concerns about postal delays. If you mail your ballot, send it at least 7 days before Election Day to ensure it's postmarked on time. If you're voting close to Election Day, use a drop box.</p>
                
                <h3>How do I track my ballot?</h3>
                <p>Visit <a href="https://voter.votewa.gov/WhereToVote.aspx" target="_blank">VoteWA.gov</a> and sign in. You'll see three possible statuses:</p>
                <ul>
                    <li><strong>Sent:</strong> The county marked your ballot as sent (this date might be in the future)</li>
                    <li><strong>Received:</strong> Your ballot arrived at the elections office and is pending signature verification</li>
                    <li><strong>Accepted:</strong> Your signature was verified and your vote will be counted</li>
                </ul>
                
                <h3>What if I don't use the secrecy envelope?</h3>
                <p>Your ballot will still be counted. The secrecy envelope is recommended but not required. You must still sign the outer oath envelope.</p>
                
                <h3>Do I need a stamp?</h3>
                <p>No. All ballot return envelopes include prepaid postage.</p>
                
                <h3>What if my signature doesn't match?</h3>
                <p>You'll receive a letter, phone call, email, or text explaining how to update your signature. You have until the county certifies the election (21 days after Election Day) to resolve the issue.</p>
                
                <h3>Can I vote at the courthouse?</h3>
                <p>The Lewis County Auditor's Office (351 NW North St, Chehalis) is a voting center where you can register, get a replacement ballot, or use accessible voting equipment. Hours are Monday-Friday, 8:30 AM-4:30 PM, and on Election Day from 8:00 AM-8:00 PM. However, Washington doesn't have traditional polling places with voting machines—you'll receive a paper ballot to complete and return.</p>
                
                <h3>What if I made a mistake on my ballot?</h3>
                <p>Contact the Lewis County Auditor's Office at (360) 740-1278 to request a replacement ballot. You can also print a replacement from <a href="https://voter.votewa.gov/" target="_blank">VoteWA.gov</a>.</p>
                
                <h3>Can I register on Election Day?</h3>
                <p>Yes. Online and mail registration closes 8 days before Election Day. After that, you can register in person at the county elections office or at a voting center through 8:00 PM on Election Day.</p>
                
                <h3>Why are results still changing days after the election?</h3>
                <p>This is normal in Washington's system. Ballots postmarked by Election Day can arrive for several days afterward. Election officials prioritize accuracy over speed. Counties have 21 days to certify results, allowing time to count every valid ballot and resolve any signature challenges.</p>
            </div>
            
            <div class="voter-section">
                <h2>Key deadlines</h2>
                
                <h3>For voters</h3>
                <ul>
                    <li><strong>8 days before Election Day:</strong> Last day to register or update your address online or by mail</li>
                    <li><strong>18 days before Election Day:</strong> Ballots are mailed; drop boxes open</li>
                    <li><strong>7 days before Election Day:</strong> Recommended last day to mail your ballot to ensure timely postmark</li>
                    <li><strong>Election Day (8:00 PM):</strong> Deadline to return ballot to drop box or have ballot postmarked</li>
                    <li><strong>Through Election Day:</strong> In-person registration available at voting centers</li>
                </ul>
                
                <h3>After Election Day</h3>
                <ul>
                    <li><strong>21 days after Election Day:</strong> Deadline to resolve signature challenges; counties certify results</li>
                    <li><strong>30 days after Election Day:</strong> Secretary of State certifies statewide results</li>
                </ul>
            </div>
            
            <div class="voter-section">
                <h2>Resources and contacts</h2>
                
                <h3>Lewis County Elections</h3>
                <p>
                    <strong>Address:</strong> 351 NW North St, Chehalis, WA 98532<br>
                    <strong>Mailing:</strong> PO Box 29, Chehalis, WA 98532<br>
                    <strong>Phone:</strong> (360) 740-1278<br>
                    <strong>Toll-free (within Lewis County):</strong> 1-800-562-6130 ext. 1278<br>
                    <strong>Email:</strong> <a href="mailto:elections@lewiscountywa.gov">elections@lewiscountywa.gov</a><br>
                    <strong>Website:</strong> <a href="https://elections.lewiscountywa.gov/" target="_blank">elections.lewiscountywa.gov</a><br>
                    <strong>Hours:</strong> Monday-Friday, 8:30 AM-4:30 PM; Election Day, 8:00 AM-8:00 PM
                </p>
                
                <a href="https://elections.lewiscountywa.gov/drop-box-locations/" class="button" target="_blank">View Drop Box Locations</a>
                <a href="https://elections.lewiscountywa.gov/current-election/election-myths-faqs/" class="button button-outline" target="_blank">Lewis County Election FAQs</a>
                
                <h3>Washington State Elections</h3>
                <p>
                    <strong>Website:</strong> <a href="https://www.sos.wa.gov/elections" target="_blank">sos.wa.gov/elections</a><br>
                    <strong>Phone:</strong> (800) 448-4881<br>
                    <strong>Email:</strong> <a href="mailto:elections@sos.wa.gov">elections@sos.wa.gov</a>
                </p>
                
                <a href="https://voter.votewa.gov/" class="button" target="_blank">VoteWA Portal</a>
                <a href="https://www.sos.wa.gov/elections/voters/securing-your-vote" class="button button-outline" target="_blank">Securing Your Vote</a>
                
                <h3>Additional resources</h3>
                <ul>
                    <li><a href="https://voter.votewa.gov/genericvoterguide.aspx" target="_blank">Online Voters' Guide</a></li>
                    <li><a href="https://www.sos.wa.gov/elections/data-research/election-data-and-maps/ballot-status-reports" target="_blank">Daily Ballot Status Reports</a></li>
                    <li><a href="https://results.vote.wa.gov/" target="_blank">Statewide Election Results</a></li>
                    <li><a href="https://www.sos.wa.gov/elections/voters/voter-registration/drop-box-and-voting-center-locations" target="_blank">Statewide Drop Box Map</a></li>
                </ul>
            </div>
            
            <div class="success-box">
                <strong>Questions or need help?</strong> Contact the Lewis County Auditor's Office at (360) 740-1278 or the Lewis County Democrats at <a href="mailto:hello@lewiscountydemocrats.org">hello@lewiscountydemocrats.org</a>
            </div>
        </div>
        
                    <?php
                    }
                    ?>
                </div>

                <?php if (!post_password_required() && (comments_open() || get_comments_number())) : ?>
                    <footer class="entry-footer">
                        <?php comments_template(); ?>
                    </footer>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
