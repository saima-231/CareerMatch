document.addEventListener('DOMContentLoaded', function() {
    // Role Switcher Logic
    const studentBtn = document.getElementById('show-student-form');
    const companyBtn = document.getElementById('show-company-form');
    const studentWrapper = document.getElementById('student-form-wrapper');
    const companyWrapper = document.getElementById('company-form-wrapper');

    // Active state uses accent-teal background and white text
    const activeClasses = ['bg-accent-teal', 'text-white']; 
    // Inactive state uses white background and accent-teal text/border
    const inactiveClasses = ['bg-white', 'text-accent-teal'];


    studentBtn.addEventListener('click', () => {
        // Show Student Form
        studentWrapper.classList.remove('hidden');
        companyWrapper.classList.add('hidden');
        
        // Style Student as Active
        studentBtn.classList.add(...activeClasses);
        studentBtn.classList.remove(...inactiveClasses);
        
        // Style Company as Inactive
        companyBtn.classList.remove(...activeClasses);
        companyBtn.classList.add(...inactiveClasses);
    });

    companyBtn.addEventListener('click', () => {
        // Show Company Form
        companyWrapper.classList.remove('hidden');
        studentWrapper.classList.add('hidden');
        
        // Style Company as Active
        companyBtn.classList.add(...activeClasses);
        companyBtn.classList.remove(...inactiveClasses);
        
        // Style Student as Inactive
        studentBtn.classList.remove(...activeClasses);
        studentBtn.classList.add(...inactiveClasses);
    });
    
    // Function to handle showing/hiding forms (Login/Register toggle)
    const toggleForm = (showLink, hideLink, formToHide, formToShow) => {
        showLink.addEventListener('click', (e) => {
            e.preventDefault();
            formToHide.classList.add('hidden');
            // Select the immediate <p> element after the form (the "Don't have an account?" link)
            formToHide.parentElement.querySelector('.form-toggle-link').classList.add('hidden'); 
            formToShow.classList.remove('hidden');
        });

        hideLink.addEventListener('click', (e) => {
            e.preventDefault();
            formToShow.classList.add('hidden');
            formToHide.classList.remove('hidden');
            // Show the "Don't have an account?" link/text again
            formToHide.parentElement.querySelector('.form-toggle-link').classList.remove('hidden');
        });
    };

    // Student Toggle Logic
    const showStudentReg = document.getElementById('show-student-register');
    const hideStudentReg = document.getElementById('hide-student-register');
    const studentLoginForms = studentWrapper.querySelector('form'); 
    const studentRegisterForm = document.getElementById('student-register-form');
    
    toggleForm(showStudentReg, hideStudentReg, studentLoginForms, studentRegisterForm);
    
    // Company Toggle Logic
    const showCompanyReg = document.getElementById('show-company-register');
    const hideCompanyReg = document.getElementById('hide-company-register');
    const companyLoginForms = companyWrapper.querySelector('form');
    const companyRegisterForm = document.getElementById('company-register-form');

    toggleForm(showCompanyReg, hideCompanyReg, companyLoginForms, companyRegisterForm);
});