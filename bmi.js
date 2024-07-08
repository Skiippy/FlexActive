// Class calculate BMI
class BMICalculator {
  constructor(weight, height) {
    this.weight = weight;
    this.height = height;
  }

  // Method to calculate BMI based on weight and height
  calculateBMI() {
    const height_in_meters = this.height / 100;
    return this.weight / (height_in_meters * height_in_meters);
  }

  // Method to determine BMI category based on BMI value
  getBMICategory(bmi) {
    if (bmi < 18.5) {
      return "Underweight";
    } else if (bmi < 24.9) {
      return "Normal weight";
    } else if (bmi < 29.9) {
      return "Overweight";
    } else {
      return "Obesity";
    }
  }

  // Method to get BMI result, including BMI value and category
  getResult() {
    const bmi = this.calculateBMI();
    const category = this.getBMICategory(bmi);
    return { bmi, category };
  }
}

// Selectors object to store references to DOM elements
const selectors = {
  form: document.getElementById("calculator"),
  result: document.getElementById("result"),
  bmi: document.getElementById("bmi"),
  category: document.getElementById("category"),
};

// Function to render BMI result to the DOM
const render = ({ bmi, category }) => {
  selectors.bmi.textContent = bmi.toFixed(2);
  selectors.category.textContent = category;
};

// Event handler for form submission
const onFormSubmit = (e) => {
  e.preventDefault();

  const form = e.target;

  const weight = Number(form.weight.value);
  const height = Number(form.height.value);

  if (isNaN(weight) || isNaN(height)) {
    alert("Weight and height must be numeric");
    return;
  }

  selectors.result.classList.remove("show");

  setTimeout(() => {
    const calc = new BMICalculator(weight, height);
    render(calc.getResult());
    selectors.result.classList.add("show");
  }, 400);
};

// Event handler for form reset
const onFormReset = () => {
  selectors.result.classList.remove("show");
};

// Event listeners for form submission and reset
selectors.form.addEventListener("submit", onFormSubmit);
selectors.form.addEventListener("reset", onFormReset);