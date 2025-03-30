<?php
/**
 * Template Name: Tax Calculator
 * Template Post Type: page
 *
 * @package LCD_Theme
 */

get_header();
?>

<main id="primary" class="site-main page-template tax-calculator-page">
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
                        the_content();
                        
                        // Start Tax Calculator HTML
                        ?>
                        <div class="tax-calculator-container">
                            <div class="calculator-panel">
                                <h2>Find out how the proposed Democratic budget will impact your household</h2>
                                <p>Republicans claim that the Democratic budget will cost Lewis County families more. Let's see if that's really true for <em>your</em> household.</p>
                                
                                <form id="tax-calculator">
                                    <div class="form-group">
                                        <label for="home-value">What is the assessed value of your home?</label>
                                        <input type="number" id="home-value" placeholder="Enter value (e.g., 300000)" min="0" step="1000">
                                        <span class="note">If you rent, enter 0</span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="household-income">What is your approximate annual household income?</label>
                                        <select id="household-income">
                                            <option value="1">Under $30,000</option>
                                            <option value="2">$30,000 - $50,000</option>
                                            <option value="3">$50,000 - $75,000</option>
                                            <option value="4" selected>$75,000 - $100,000</option>
                                            <option value="5">$100,000 - $150,000</option>
                                            <option value="6">$150,000 - $200,000</option>
                                            <option value="7">Over $200,000</option>
                                            <option value="8">Over $1,000,000</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="household-size">How many people are in your household?</label>
                                        <select id="household-size">
                                            <option value="1">1 (Single)</option>
                                            <option value="2" selected>2 (Couple)</option>
                                            <option value="3">3 (Small family)</option>
                                            <option value="4">4 (Family)</option>
                                            <option value="5">5+ (Large family)</option>
                                        </select>
                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Additional factors (check all that apply):</label>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="outdoors-user" name="outdoors-user">
                                            <label for="outdoors-user">I/my family purchase Discover Pass for state parks access</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="hunting-fishing" name="hunting-fishing">
                                            <label for="hunting-fishing">I/my family purchase hunting or fishing licenses</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="high-mileage" name="high-mileage">
                                            <label for="high-mileage">I/my family drive more than 10,000 miles per year</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="public-school" name="public-school">
                                            <label for="public-school">Children in public school</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="college-student" name="college-student">
                                            <label for="college-student">Someone in household attends or plans to attend college</label>
                                        </div>
                                        
                                        <div class="checkbox-group">
                                            <input type="checkbox" id="medicaid" name="medicaid">
                                            <label for="medicaid">Someone in household uses Medicaid/Apple Health</label>
                                        </div>
                                    </div>
                                    
                                    <button type="button" onclick="calculateImpact()">Calculate My Impact</button>
                                </form>
                            </div>
                            
                            <div id="results" class="results calculator-panel">
                                <h2>Your Personalized Budget Impact Results</h2>
                                
                                <div id="savings-message" class="savings-message"></div>
                                
                                <div class="tax-breakdown">
                                    <div class="tax-item">
                                        <h3>Property Tax Impact</h3>
                                        <p>Under the current 1% cap, your property taxes would increase by approximately <span id="current-property" class="highlight">$0</span> next year.</p>
                                        <p>Under the Democratic budget (with inflation+population growth cap), your property taxes would increase by approximately <span id="new-property" class="highlight">$0</span> next year.</p>
                                        <p>Net property tax difference: <span id="property-difference"></span></p>
                                    </div>
                                    
                                    <div class="tax-item">
                                        <h3>Sales Tax Impact</h3>
                                        <p>Under the current 6.5% state sales tax rate, you pay approximately <span id="current-sales" class="highlight">$0</span> in state sales tax each year.</p>
                                        <p>Under the Democratic budget's reduced 6.0% state sales tax rate, you would pay approximately <span id="new-sales" class="highlight">$0</span> in state sales tax each year.</p>
                                        <p>Net sales tax savings: <span id="sales-difference" class="positive">$0</span></p>
                                    </div>
                                </div>
                                
                                <div id="additional-impacts" style="display: none;">
                                    <button class="accordion">Additional Fee Impacts (+/- <span id="additional-total">$0</span>)</button>
                                    <div class="panel">
                                        <div id="outdoors-impact" style="margin: 10px 0; display: none;">
                                            <p><strong>Discover Pass:</strong> <span id="outdoors-value"></span></p>
                                        </div>
                                        <div id="hunting-impact" style="margin: 10px 0; display: none;">
                                            <p><strong>Hunting/Fishing License:</strong> <span id="hunting-value"></span></p>
                                        </div>
                                        <div id="gas-impact" style="margin: 10px 0; display: none;">
                                            <p><strong>Gas Tax Impact:</strong> <span id="gas-value"></span></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="republican-impacts" style="display: none; margin-top: 20px;">
                                    <button class="accordion" style="background-color: #ffeeee; color: #d83933;">Potential Republican Budget Impacts (+/- <span id="republican-total">$0</span>)</button>
                                    <div class="panel">
                                        <p style="margin: 10px 0;"><strong>Under the Republican "no new taxes" budget, your household could face:</strong></p>
                                        <div id="education-impact" style="margin: 10px 0; display: none;">
                                            <p><strong>K-12 Education:</strong> <span id="education-value"></span></p>
                                        </div>
                                        <div id="college-impact" style="margin: 10px 0; display: none;">
                                            <p><strong>Higher Education:</strong> <span id="college-value"></span></p>
                                        </div>
                                        <div id="medicaid-impact" style="margin: 10px 0; display: none;">
                                            <p><strong>Healthcare Access:</strong> <span id="medicaid-value"></span></p>
                                        </div>
                                        <p style="margin-top: 15px; font-size: 0.9em;">Note: These estimates are based on historical patterns from previous cuts during the 2009-2013 recession and stated Republican budget priorities. The GOP budget doesn't specifically detail these cuts (they refuse to answer what would be cut), but they would need to occur to balance the budget without new revenues.</p>
                                    </div>
                                </div>
                                
                                <div class="result-box">
                                    <h3>Overall Impact on Your Household</h3>
                                    <p>When combining all tax changes, the Democratic budget would result in a <span id="overall-impact"></span> for your household next year.</p>
                                    <p id="wealth-tax-message"></p>
                                    <p id="republican-comparison-message" style="margin-top: 15px;"></p>
                                </div>
                                
                                <div class="benefit-list">
                                    <h3>What Lewis County Gets From This Budget</h3>
                                    <ul>
                                        <li><strong>Better funded schools</strong> - Increased funding for K-12 education, especially special education programs and operational costs</li>
                                        <li><strong>Improved emergency services</strong> - Local governments can better fund police, fire, and emergency medical services without raising local levies</li>
                                        <li><strong>Maintained infrastructure</strong> - Roads, bridges, and public facilities get needed maintenance</li>
                                        <li><strong>Preserved social services</strong> - Programs for seniors, children, and vulnerable residents continue</li>
                                        <li><strong>Lower sales tax burden</strong> - The 0.5% reduction helps everyone but especially benefits lower and middle-income households</li>
                                    </ul>
                                </div>
                                
                                <div class="disclaimer">
                                    <p><small>This calculator provides estimates based on current budget proposals. Final impacts may vary based on budget negotiations and your specific circumstances. For more details, <a href="https://washingtonstatestandard.com/2025/03/24/democrats-in-washington-legislature-pitch-competing-budget-plans/" target="_blank">read the full budget proposal</a>.</small></p>
                                </div>
                                
                                <p>Want to learn more about the 2025 Washington State budget proposals? <a href="https://washingtonstatestandard.com/2025/03/24/democrats-in-washington-legislature-pitch-competing-budget-plans/" target="_blank">Read the details here</a> or <a href="https://lewiscountydemocrats.org/the-hidden-costs-of-budget-austerity-why-no-new-taxes-comes-at-a-price-lewis-county-cant-afford/" target="_blank">read our breakdown of how the GOP's austerity budget would impact Lewis County.</a></p>
                            </div>
                        </div>
                        <?php
                        // End Tax Calculator HTML

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'lcd-theme'),
                            'after'  => '</div>',
                        ));
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