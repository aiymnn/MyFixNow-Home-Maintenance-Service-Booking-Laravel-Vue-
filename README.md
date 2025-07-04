# MyFixNow - Home Maintenance Service Booking System

🚧 **This project is still under development for testing and learning purposes.**

MyFixNow is a **Home Maintenance Service Booking System** where:

- **Customers** can browse services, view details, book services, and pay using Stripe.
- **Providers** can add services, manage service areas, add availability slots, and manage bookings.
- **Admins** can manage service categories and service areas for providers.

---

## ⚙️ Tech Stack

- **Laravel 12**
- **Vue 3**
- **Tailwind CSS**
- **Inertia.js**
- **Stripe API** for payment
- **MySQL**
- **Laravel Queues (`php artisan queue:work`)** for emails and receipt generation

---

## 🖼️ Screenshots

### 🏠 Home
![Home](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/Home.png?raw=true)

---

## 👤 Customer

1️⃣ **Browse Services**  
![Browse Services](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20browse%20services.png?raw=true)

2️⃣ **View Service Details**  
![View Details](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20view%20service%20details.png?raw=true)

3️⃣ **Add Booking (choose date & slot)**  
![Add Booking](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20add%20booking.png?raw=true)

4️⃣ **View Booking & Payment/Cancel after Provider accepted**  
![View Booking](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20view%20booking.png?raw=true)

5️⃣ **Payment using Stripe**  
![Payment via Stripe](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20payment%20via%20Sripe.png?raw=true)

---

## 📧 Customer Email Notifications

- Receive after adding booking  
  ![Email after Add Booking](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20email%20receive%20after%20add%20booking.png?raw=true)

- Receive after provider accepts booking  
  ![Email after Provider Accept](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20email%20receive%20after%20provider%20accept%20booking.png?raw=true)

- Receive after making payment  
  ![Email after Payment](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20email%20after%20payment.png?raw=true)

- Download receipt from email  
  ![Download Receipt](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/customer%20-%20download%20receipt.png?raw=true)

---

## 👨‍🔧 Provider

1️⃣ **Add New Service**  
![Add Service](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20add%20new%20service.png?raw=true)

2️⃣ **Add Service Area**  
![Add Area](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20add%20area%20service.png?raw=true)

3️⃣ **Add Slot for Each Service**  
![Add Slot](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20add%20slot.png?raw=true)

4️⃣ **View Services**  
![View Service](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20view%20service.png?raw=true)

5️⃣ **Update Booking Status (accept, reject, in progress, completed)**  
![Update Booking Status](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20update%20status%20booking%20(accepted%20or%20rejected).png?raw=true)

---

## 📧 Provider Email Notifications

- Receive after customer adds booking  
  ![Receive Email Booking](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20receive%20email%20after%20customer%20add%20booking.png?raw=true)

- Receive after customer makes payment  
  ![Receive Email Payment](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/provider%20-%20receive%20email%20after%20custome%20rmake%20payment.png?raw=true)

---

## 🛠️ Admin

1️⃣ **Add Category of Services**  
![Add Category](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/admin%20-%20add%20category.png?raw=true)

2️⃣ **Add Service Area**  
![Add Service Area](https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-/blob/main/screenshorts/admin%20-%20add%20service%20area.png?raw=true)

---

## 🚀 How to Clone & Setup

To run this project locally:

```bash
git clone https://github.com/aiymnn/MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-.git

cd MyFixNow-Home-Maintenance-Service-Booking-Laravel-Vue-

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy .env and configure your environment variables
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Build frontend assets
npm run build

# Start Laravel server
php artisan serve

# (Optional) Run queue worker for email notifications and receipts
php artisan queue:work
