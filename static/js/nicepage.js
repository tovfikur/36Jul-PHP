document.addEventListener('DOMContentLoaded', function () {
    const nextBtn = document.querySelector('.u-btn-step-next');
    const prevBtn = document.querySelector('.u-btn-step-prev');
    const submitBtn = document.querySelector('.u-btn-submit');
    const form = document.querySelector('form.u-inner-form');
    const formSteps = document.querySelectorAll('.u-carousel-item');
    
    let currentStep = 0;

    function showStep(index) {
        formSteps.forEach((step, idx) => {
            step.classList.toggle('u-active', idx === index);
        });
        
        prevBtn.classList.toggle('u-hidden', currentStep === 0);
        nextBtn.classList.toggle('u-hidden', currentStep === formSteps.length - 1);
        submitBtn.classList.toggle('u-hidden', currentStep !== formSteps.length - 1);
    }

    nextBtn.addEventListener('click', function (event) {
        event.preventDefault();
        if (currentStep < formSteps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function (event) {
        event.preventDefault();
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    submitBtn.addEventListener('click', function (event) {
        event.preventDefault();


        // Get the details and press release links
        const detailsTextarea = document.getElementById('textarea-f667');
        const pressReleaseLinks = document.getElementById('press-release-links').value.trim();

        // Append press release links to the bottom of the details text
        if (pressReleaseLinks) {
            let detailsText = detailsTextarea.value.trim();
            if (!detailsText.endsWith('\n')) {
                detailsText += '\n';
            }
            detailsTextarea.value = detailsText + '\n' + pressReleaseLinks;
        }



        const formData = new FormData(form);
        
        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = '/templates/success.html';  // Redirect to the success page
            } else {
                alert('Submission failed: ' + (data.message || 'Unknown error.'));
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    });

    showStep(currentStep);
});



jQuery(document).ready(function($){
    // Automatically open popup when the page is fully loaded
    //$('.popup').addClass('is-visible');

    // Close popup
    $('.popup').on('click', function(event){
      if( $(event.target).is('.popup-close') || $(event.target).is('.popup') ) {
        event.preventDefault();
        $(this).removeClass('is-visible');
      }
    });

    // Close popup when clicking the ESC keyboard button
    $(document).keyup(function(event){
      if(event.which == '27'){ // 27 is the keycode for the ESC key
        $('.popup').removeClass('is-visible');
      }
    });

    // Create and style a sticky button
    const stickyButton = $('<button class="sticky-popup-button">CONTRIBUTE</button>');
    $('body').append(stickyButton);

// Style the button with CSS
stickyButton.css({
    position: 'fixed',
    bottom: '20px',
    right: '20px',
    padding: '12px 24px',
    backgroundColor: '#007BFF',
    color: '#fff',
    border: 'none',
    borderRadius: '25px', // More rounded corners
    boxShadow: '0 4px 8px rgba(0, 123, 255, 0.2)', // Subtle shadow for depth
    fontSize: '16px', // Larger font size
    fontWeight: 'bold', // Bold text
    transition: 'background-color 0.3s, transform 0.2s', // Smooth transitions
    cursor: 'pointer',
    zIndex: 1000
});

// Add hover effect
stickyButton.on('mouseenter', function() {
    $(this).css({
        backgroundColor: '#0056b3', // Darker shade on hover
        transform: 'scale(1.05)' // Slightly increase size
    });
}).on('mouseleave', function() {
    $(this).css({
        backgroundColor: '#007BFF', // Reset to original color
        transform: 'scale(1)' // Reset size
    });
});

    // Add click event to the sticky button to open the popup
    stickyButton.on('click', function(){
        $('.popup').addClass('is-visible');
    });
});



$(document).ready(function() {
    // Show modal when the page is fully loaded
    // $('.popup').addClass('is-visible');

    // Handle form submission
    $('#uniqueForm').on('submit', function(event) {
      event.preventDefault(); // Prevent default form submission

      // Gather form data
      const formData = {
        type: $('input[name="type"]:checked').val(),
        name: $('#name').val(),
        note: $('#note').val()
      };

      // Prepare the email body
      const emailBody = `
        Type: ${formData.type}
        Name: ${formData.name}
        Describe/Note: ${formData.note}
      `;

      // Construct the mailto URL
      const mailtoURL = `mailto:team@legalizedbd.com?subject=Form Submission&body=${encodeURIComponent(emailBody)}`;

      // Redirect to the mailto URL
      window.location.href = mailtoURL;
    });

    // Close popup on clicking close button
    $('.popup').on('click', function(event) {
      if ($(event.target).is('.popup-close') || $(event.target).is('.popup')) {
        event.preventDefault();
        $(this).removeClass('is-visible');
      }
    });

    // Close popup when pressing Esc key
    $(document).keyup(function(event) {
      if (event.which == '27') {
        $('.popup').removeClass('is-visible');
      }
    });
  });
