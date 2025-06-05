
function scrollCategories(direction) {
    const container = document.querySelector('.category-chips-scroll');
    const scrollAmount = 200; // Adjust this value to control scroll distance
    
    if (direction === 'left') {
        container.scrollLeft -= scrollAmount;
    } else {
        container.scrollLeft += scrollAmount;
    }
}

// Add scroll event listener to show/hide arrows
document.querySelector('.category-chips-scroll').addEventListener('scroll', function() {
    const container = this;
    const leftArrow = document.querySelector('.category-chips-arrow-left');
    const rightArrow = document.querySelector('.category-chips-arrow-right');
    
    // Show/hide left arrow
    if (container.scrollLeft > 0) {
        leftArrow.style.display = 'flex';
    } else {
        leftArrow.style.display = 'none';
    }
    
    // Show/hide right arrow
    if (container.scrollLeft < (container.scrollWidth - container.clientWidth)) {
        rightArrow.style.display = 'flex';
    } else {
        rightArrow.style.display = 'none';
    }
});

// Initial check for arrows
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.category-chips-scroll');
    const leftArrow = document.querySelector('.category-chips-arrow-left');
    const rightArrow = document.querySelector('.category-chips-arrow-right');
    
    // Hide left arrow initially
    leftArrow.style.display = 'none';
    
    // Show right arrow if content is scrollable
    if (container.scrollWidth > container.clientWidth) {
        rightArrow.style.display = 'flex';
    } else {
        rightArrow.style.display = 'none';
    }
});
