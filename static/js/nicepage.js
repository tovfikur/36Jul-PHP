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
    $('.popup').addClass('is-visible');

    // Close popup
    $('.popup').on('click', function(event){
      if( $(event.target).is('.popup-close') || $(event.target).is('.popup') ) {
        event.preventDefault();
        $(this).removeClass('is-visible');
      }
    });

    // Close popup when clicking the esc keyboard button
    $(document).keyup(function(event){
      if(event.which == '27'){ // 27 is the keycode for the ESC key
        $('.popup').removeClass('is-visible');
      }
    });
});


$(document).ready(function() {
    // Show modal when the page is fully loaded
    $('.popup').addClass('is-visible');

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
