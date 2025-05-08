To start project you need to copy `.env.dist` to `.env`:

```bash
cp .env.dist .env
```

---

## To start project by using docker

Start docker compose stack:

```bash
docker compose up -d
```

and install packages:

```bash
docker-compose run --rm php composer install 
```

When everything is successfully installed project should be available in http://localhost:8000

---

## To start project directly on your machine

Install packages:

```bash
composer install
```

After import provided `dump.sql` into your database or execute migrations and seed commands:

```bash
yii migrate --migrationPath=@yii/rbac/migrations
yii migrate
yii seed
```

Change connection details in `config/db.php` if `getenv` can't read environment values.

---

## Employee Access Check API

### Check if an employee has access to a construction site on a specific date

**Endpoint:** `/api/check-employee-access`

**Methods:** GET

**Parameters:**
- `employee_id` - ID of the employee to check access for
- `construction_site_id` - ID of the construction site
- `date` - Date to check access for, in YYYY-MM-DD format


**Example GET Request:**
```
/api/check-employee-access?employee_id=3&construction_site_id=2&date=2025-05-07
```

**Example Response for Successful Request:**
```json
{
  "success": true,
  "has_access": true,
  "employee_id": 3,
  "construction_site_id": 2,
  "date": "2025-05-07"
}
```

**Example Response for Failed Request:**
```json
{
  "success": false,
  "message": "Missing required parameters. Please provide employee_id, construction_site_id, and date."
}
```
