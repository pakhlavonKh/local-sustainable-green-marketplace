# GreenMarket - Deployment Guide

## Docker Deployment

### Local Development with Docker

1. **Build and run locally:**
   ```bash
   docker build -t greenmarket .
   docker run -p 8080:80 greenmarket
   ```

2. **Or use Docker Compose:**
   ```bash
   docker-compose up --build
   ```

   Access the application at `http://localhost:8080`

### Deploying to Render

#### Option 1: Using Render Blueprint (Recommended)

1. **Push your code to GitHub**
   ```bash
   git add .
   git commit -m "Add Docker configuration"
   git push origin main
   ```

2. **Connect to Render:**
   - Go to [Render Dashboard](https://dashboard.render.com/)
   - Click "New" → "Blueprint"
   - Connect your GitHub repository
   - Render will automatically detect `render.yaml` and deploy

3. **Configure Environment Variables:**
   - In Render dashboard, go to your service
   - Add environment variables:
     - `MONGODB_URI` (if using MongoDB)
     - `MONGODB_DATABASE` (default: greenmarket)

#### Option 2: Manual Web Service Setup

1. **Create New Web Service:**
   - Go to Render Dashboard
   - Click "New" → "Web Service"
   - Connect your GitHub repository

2. **Configure Service:**
   - **Name:** greenmarket
   - **Region:** Oregon (or your preferred region)
   - **Branch:** main
   - **Runtime:** Docker
   - **Instance Type:** Free (or paid plan)

3. **Environment Variables:**
   Add the following in the Environment section:
   - `MONGODB_URI` = your MongoDB connection string (if needed)
   - `MONGODB_DATABASE` = greenmarket

4. **Deploy:**
   - Click "Create Web Service"
   - Render will build and deploy automatically

### Environment Configuration

Copy `.env.example` to `.env` for local development:
```bash
cp .env.example .env
```

Edit `.env` with your actual MongoDB credentials if using MongoDB.

### Persistent Storage on Render

For file uploads and data persistence:

1. **Add a Disk** in Render Dashboard:
   - Go to your service settings
   - Click "Disks" → "Add Disk"
   - Mount Path: `/var/www/html/uploads`
   - Size: 1GB (or as needed)

2. **For data directory:**
   - Add another disk
   - Mount Path: `/var/www/html/data`

### Monitoring and Logs

- View logs in Render Dashboard under "Logs" tab
- Set up health checks (already configured in `render.yaml`)

### Scaling

To scale your application:
- Go to service settings
- Change instance type from Free to a paid plan
- Adjust number of instances if needed

### Custom Domain

1. Go to service "Settings" → "Custom Domain"
2. Add your domain
3. Update DNS records as instructed

## Testing Docker Build Locally

```bash
# Build the image
docker build -t greenmarket .

# Run the container
docker run -d -p 8080:80 --name greenmarket-test greenmarket

# View logs
docker logs greenmarket-test

# Stop and remove
docker stop greenmarket-test
docker rm greenmarket-test
```

## Troubleshooting

### Permission Issues
If you encounter permission errors:
```dockerfile
RUN chown -R www-data:www-data /var/www/html
```

### MongoDB Connection
Ensure your MongoDB Atlas cluster allows connections from Render's IP addresses (0.0.0.0/0 for testing).

### Uploads Not Persisting
Make sure you've added a persistent disk in Render for `/var/www/html/uploads`.

## Notes

- The application runs on Apache with PHP 8.2
- MongoDB extension is pre-installed
- Data and uploads directories are created automatically
- Free tier on Render may spin down after inactivity
