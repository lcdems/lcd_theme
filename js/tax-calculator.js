/**
 * Tax Calculator JavaScript
 * 
 * Handles the functionality for the budget impact calculator
 */

// Helper functions
function suggestSpending(amount) {
    document.getElementById('annual-spending').value = amount;
}

// Accordion functionality
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('accordion')) {
        e.target.classList.toggle('active');
        const panel = e.target.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    }
});

function calculateImpact() {
    // Get input values
    const homeValue = parseFloat(document.getElementById('home-value').value) || 0;
    const incomeLevel = parseInt(document.getElementById('household-income').value);
    const householdSize = parseInt(document.getElementById('household-size').value);
    
    // Calculate estimated taxable spending based on income and household size
    let annualSpending = estimateTaxableSpending(incomeLevel, householdSize);
    
    const isOutdoorsUser = document.getElementById('outdoors-user').checked;
    const isHuntingFishing = document.getElementById('hunting-fishing').checked;
    const isHighMileage = document.getElementById('high-mileage').checked;
    const hasPublicSchool = document.getElementById('public-school').checked;
    const hasCollegeStudent = document.getElementById('college-student').checked;
    const usesMedicaid = document.getElementById('medicaid').checked;
    
    // Constants
    const INFLATION_RATE = 0.025; // 2.5% inflation
    const POPULATION_GROWTH = 0.005; // 0.5% population growth
    
    // Calculate property tax impacts
    const currentPropertyTaxIncrease = homeValue * 0.01 * 0.01; // 1% of assessed value, then 1% increase
    
    // Democratic plan: inflation + population growth, capped at 3%
    const demPropertyTaxRate = Math.min(INFLATION_RATE + POPULATION_GROWTH, 0.03);
    const newPropertyTaxIncrease = homeValue * 0.01 * demPropertyTaxRate;
    
    const propertyTaxDifference = newPropertyTaxIncrease - currentPropertyTaxIncrease;
    
    // Calculate sales tax impacts
    const currentSalesTax = annualSpending * 0.065;
    const newSalesTax = annualSpending * 0.06;
    const salesTaxSavings = currentSalesTax - newSalesTax;
    
    // Display the estimated annual spending used in the calculation
    const spendingMessage = `Based on your household's size and income level, we estimate your annual taxable spending at approximately $${annualSpending.toLocaleString()}. `;
    document.getElementById('current-sales').parentNode.innerHTML = 
        spendingMessage + 
        `Under the current 6.5% state sales tax rate, you pay approximately <span id="current-sales" class="highlight">$${currentSalesTax.toFixed(2)}</span> in state sales tax each year.`;
    
    // Calculate additional impacts
    let additionalImpacts = 0;
    let hasAdditionalImpacts = false;
    
    // Discover Pass (State Parks)
    if (isOutdoorsUser) {
        // Proposal would increase annual pass from $30 to $45 (50% increase)
        const discoverPassImpact = -15;
        additionalImpacts += discoverPassImpact;
        hasAdditionalImpacts = true;
        
        document.getElementById('outdoors-impact').style.display = 'block';
        document.getElementById('outdoors-value').textContent = `The Discover Pass would increase from $30 to $45 annually ($15 increase)`;
        document.getElementById('outdoors-value').className = 'negative';
    } else {
        document.getElementById('outdoors-impact').style.display = 'none';
    }
    
    // Hunting/Fishing Licenses
    if (isHuntingFishing) {
        // Proposal would increase typical license fees by about 38% (~$50 increase on average)
        const licenseFeeImpact = -50;
        additionalImpacts += licenseFeeImpact;
        hasAdditionalImpacts = true;
        
        document.getElementById('hunting-impact').style.display = 'block';
        document.getElementById('hunting-value').textContent = `License fees would increase approximately 38% (about $50 more on average)`;
        document.getElementById('hunting-value').className = 'negative';
    } else {
        document.getElementById('hunting-impact').style.display = 'none';
    }
    
    // Gas Tax for High Mileage Drivers
    if (isHighMileage) {
        // Proposal could increase gas tax by 9¢/gallon
        // Assuming 10,000 miles at 25mpg = 400 gallons * 0.09 = $36
        const gasTaxImpact = -36;
        additionalImpacts += gasTaxImpact;
        hasAdditionalImpacts = true;
        
        document.getElementById('gas-impact').style.display = 'block';
        document.getElementById('gas-value').textContent = `The proposed 9¢/gallon gas tax increase would cost about $36 more annually (10k miles)`;
        document.getElementById('gas-value').className = 'negative';
    } else {
        document.getElementById('gas-impact').style.display = 'none';
    }
    
    // Republican budget impacts
    let republicanImpacts = 0;
    let hasRepublicanImpacts = false;
    
    // K-12 Education Cuts
    if (hasPublicSchool) {
        // Republicans propose efficiency cuts that could affect class sizes and programs
        const educationImpact = -750; // Based on previous cuts during 2009-2013 recession
        republicanImpacts += educationImpact;
        hasRepublicanImpacts = true;
        
        document.getElementById('education-impact').style.display = 'block';
        document.getElementById('education-value').textContent = `K-12 funding cuts under Republican budgets could lead to larger class sizes and reduced programs (estimated ~$750 per student impact)`;
        document.getElementById('education-value').className = 'negative';
    } else {
        document.getElementById('education-impact').style.display = 'none';
    }
    
    // Higher Education Cuts
    if (hasCollegeStudent) {
        // Republican plan would limit higher ed funding increases, likely resulting in tuition hikes
        const tuitionImpact = -1200; // Based on historic patterns when state funding is cut
        republicanImpacts += tuitionImpact;
        hasRepublicanImpacts = true;
        
        document.getElementById('college-impact').style.display = 'block';
        document.getElementById('college-value').textContent = `Higher education funding constraints could lead to an estimated $1,200 additional tuition/fees per year at state colleges`;
        document.getElementById('college-value').className = 'negative';
    } else {
        document.getElementById('college-impact').style.display = 'none';
    }
    
    // Medicaid/Healthcare Impacts
    if (usesMedicaid) {
        // Republican plan to find "efficiencies" in Medicaid could reduce access/benefits
        const medicaidImpact = -500; // Estimated value of reduced services or added out-of-pocket costs
        republicanImpacts += medicaidImpact;
        hasRepublicanImpacts = true;
        
        document.getElementById('medicaid-impact').style.display = 'block';
        document.getElementById('medicaid-value').textContent = `Medicaid "efficiencies" could lead to reduced services or eligibility, potentially costing families $500+ annually`;
        document.getElementById('medicaid-value').className = 'negative';
    } else {
        document.getElementById('medicaid-impact').style.display = 'none';
    }
    
    // Display Republican impacts section if there are any
    if (hasRepublicanImpacts) {
        document.getElementById('republican-impacts').style.display = 'block';
        document.getElementById('republican-total').textContent = republicanImpacts.toFixed(2);
        
        // Initialize accordion
        const republicanAccordion = document.querySelector('#republican-impacts .accordion');
        const republicanPanel = republicanAccordion.nextElementSibling;
        republicanPanel.style.maxHeight = republicanPanel.scrollHeight + "px";
    } else {
        document.getElementById('republican-impacts').style.display = 'none';
    }
    
    // Display additional impacts section if there are any
    if (hasAdditionalImpacts) {
        document.getElementById('additional-impacts').style.display = 'block';
        document.getElementById('additional-total').textContent = additionalImpacts.toFixed(2);
        
        // Initialize accordion
        const accordion = document.querySelector('.accordion');
        const panel = accordion.nextElementSibling;
        panel.style.maxHeight = panel.scrollHeight + "px";
    } else {
        document.getElementById('additional-impacts').style.display = 'none';
    }
    
    // Calculate net impact
    const netImpact = salesTaxSavings - propertyTaxDifference + additionalImpacts;
    
    // Add a comparison to Republican budget
    if (hasRepublicanImpacts) {
        const comparisonDifference = netImpact - republicanImpacts;
        document.getElementById('republican-comparison-message').style.display = 'block';
        
        if (comparisonDifference > 0) {
            document.getElementById('republican-comparison-message').innerHTML = 
                `<strong class="positive">Compared to the Republican budget plan:</strong> Your household would be approximately $${Math.abs(comparisonDifference).toFixed(2)} better off under the Democratic budget than under the Republican "no new taxes" budget that would likely cut services you use.`;
        } else {
            document.getElementById('republican-comparison-message').innerHTML = 
                `<strong class="negative">Compared to the Republican budget plan:</strong> Your household would be approximately $${Math.abs(comparisonDifference).toFixed(2)} better off under the Republican budget, if you don't consider the potential negative effects of service cuts on your community.`;
        }
    } else {
        document.getElementById('republican-comparison-message').style.display = 'none';
    }
    
    // Display results
    document.getElementById('current-property').textContent = '$' + currentPropertyTaxIncrease.toFixed(2);
    document.getElementById('new-property').textContent = '$' + newPropertyTaxIncrease.toFixed(2);
    
    if (propertyTaxDifference > 0) {
        document.getElementById('property-difference').className = 'negative';
        document.getElementById('property-difference').textContent = '-$' + propertyTaxDifference.toFixed(2);
    } else {
        document.getElementById('property-difference').className = 'positive';
        document.getElementById('property-difference').textContent = '+$' + Math.abs(propertyTaxDifference).toFixed(2);
    }
    
    document.getElementById('new-sales').textContent = '$' + newSalesTax.toFixed(2);
    document.getElementById('sales-difference').textContent = '+$' + salesTaxSavings.toFixed(2);
    
    if (netImpact >= 0) {
        document.getElementById('overall-impact').className = 'positive';
        document.getElementById('overall-impact').textContent = 'net savings of $' + netImpact.toFixed(2);
        document.getElementById('savings-message').innerHTML = '✅ You would <span class="positive">SAVE MONEY</span> under the Democratic budget plan!';
    } else {
        document.getElementById('overall-impact').className = 'negative';
        document.getElementById('overall-impact').textContent = 'net cost of $' + Math.abs(netImpact).toFixed(2);
        document.getElementById('savings-message').innerHTML = '⚠️ Your costs would <span class="negative">increase slightly</span> under the Democratic budget plan';
    }
    
    // Display income-specific messages
    if (incomeLevel >= 8) { // Over $1M income
        document.getElementById('wealth-tax-message').innerHTML = 'Note: If your household has over $50 million in financial assets (stocks, bonds, etc.), you would be subject to the wealth tax. Less than 0.1% of Washingtonians fall into this category.';
    } else {
        document.getElementById('wealth-tax-message').innerHTML = 'You would <strong>NOT</strong> be affected by the wealth tax, which only applies to households with over $50 million in financial assets (approximately 4,300 households out of 3 million+ Washington households).';
    }
    
    // Show results
    document.getElementById('results').style.display = 'block';
    
    // Scroll to results
    document.getElementById('results').scrollIntoView({behavior: 'smooth'});
}

// Function to estimate taxable spending based on household demographics
function estimateTaxableSpending(incomeLevel, householdSize) {
    // Income brackets
    const incomes = [
        0,            // 0 = Prefer not to say (should no longer be used)
        25000,        // 1 = Under $30,000
        40000,        // 2 = $30,000 - $50,000
        62500,        // 3 = $50,000 - $75,000
        87500,        // 4 = $75,000 - $100,000
        125000,       // 5 = $100,000 - $150,000
        175000,       // 6 = $150,000 - $200,000
        250000,       // 7 = Over $200,000
        1500000       // 8 = Over $1,000,000
    ];
    
    // Base spending by income (percentage of income spent on taxable goods)
    const baseSpendingRates = [
        0.30,         // 0 = Prefer not to say (no longer used)
        0.25,         // 1 = Under $30,000
        0.28,         // 2 = $30,000 - $50,000
        0.30,         // 3 = $50,000 - $75,000
        0.32,         // 4 = $75,000 - $100,000
        0.35,         // 5 = $100,000 - $150,000
        0.38,         // 6 = $150,000 - $200,000
        0.40,         // 7 = Over $200,000
        0.25          // 8 = Over $1,000,000 (lower rate as more goes to savings/investments)
    ];
    
    // Household size multipliers (adjust spending based on household size)
    const householdMultipliers = [
        1.0,    // Not used
        1.0,    // 1 person
        1.7,    // 2 people
        2.2,    // 3 people
        2.6,    // 4 people
        3.0     // 5+ people
    ];
    
    // Calculate base spending for the income level
    const income = incomes[incomeLevel];
    const baseRate = baseSpendingRates[incomeLevel];
    const baseSpending = income * baseRate;
    
    // Apply household size multiplier for non-housing expenses
    const householdMultiplier = householdMultipliers[householdSize];
    const adjustedSpending = baseSpending * householdMultiplier;
    
    // Return the estimated annual taxable spending
    return Math.round(adjustedSpending);
} 