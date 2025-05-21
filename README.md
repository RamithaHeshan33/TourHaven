# TourHaven

TourHaven is a web application that connects tourists with local guiders.

## Features

- User registration and login for tourists and guiders.
- Tourists can search for guiders/cars.
- Tourists can post trip requests and manage them.
- Tourists can make payments and rate guiders/trips.
- Guiders can manage their vehicles.
- Guiders can find and accept job/taxi requests.
- Guiders can manage their accepted jobs and update job statuses.

## Technologies

- PHP
- MySQL
- HTML, CSS, JavaScript
- Google API Client
- Stripe PHP library

## Setup/Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/TourHaven.git 
   ```
   (Replace with the actual URL if available)

2. **Database setup:**
   - Create a MySQL database named `tourhaven`.
   - Set up the database schema. (Note: Table structure details are not yet provided in this README.)

3. **Install dependencies:**
   Run `composer install` in the project root directory.

4. **Environment variables:**
   - Create a `.env` file in the project root.
   - Add your Google API credentials to the `.env` file:
     ```
     GOOGLE_CLIENT_ID=your_google_client_id
     GOOGLE_CLIENT_SECRET=your_google_client_secret
     ```

5. **Web server configuration:**
   Configure your web server (e.g., Apache, Nginx) to point to the project's root directory as the document root.

## Usage

Navigate to the respective login pages (`index.php` for the main login, `Tourists/login.php`, or `Guiders/login.php`).

### For Tourists:

- Register for a new account or log in.
- Search for available guiders or specific tour packages.
- View guider profiles and their listed services/vehicles.
- Book a tour or taxi by providing necessary details.
- Make payments for booked services.
- After the trip, provide a rating or feedback.

### For Guiders:

- Register for a new account or log in.
- Create and manage your profile.
- Add and manage your vehicle details (if applicable).
- View available job postings or taxi requests from tourists.
- Accept requests that match your services.
- Update the status of your ongoing jobs.

## Contributing

We welcome contributions to TourHaven! Please follow these guidelines:

1. **Fork the repository.**
2. **Create a new branch** for your feature or bug fix:
   ```bash
   git checkout -b feature-name 
   # or
   git checkout -b bugfix-name
   ```
3. **Make your changes** and commit them with clear and descriptive messages.
4. **Push your changes** to your forked repository.
5. **Create a pull request** to the main repository's `main` (or `master`) branch.
6. Ensure your code follows the **existing coding style**.
7. **Add or update tests** if applicable.