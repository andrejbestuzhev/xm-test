## Test task

# Deploy:
1. set AlphaVantage api token into .env "ALPHA_VANTAGE_API_KEY"
2. Add desired symbols to ALPHA_VANTAGE_SYMBOLS variable
3. docker-compose up

# Test:
1. Stock values: http://localhost:8000/stocks
2. Specific stock value: http://localhost:8000/stocks/IBM (or whatever)
3. Indications: http://localhost:8000/
4. To run tests: ```docker-compose exec api php artisan test```

# Descriptions
- Main app launched as ```php artisan serve```
- Cron task is launched in separate container with a little hack (sleep 60) to determine it's status if task failed. Cron is not supposed for that.