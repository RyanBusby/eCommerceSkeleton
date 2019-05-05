# [Online Store for Downloadable Content](https://nodal-thunder-239519.appspot.com/)

[click to see sample site](https://nodal-thunder-239519.appspot.com/)

To deploy to GCP:

 - Create relations in a database from createtables.py on a GCP PostgreSQL instance

 - Insert content into products table, file and sample file are the src for the content, and the sample content

 - Fill in sample_app.yaml with valid credentials (PostgreSQL instance info, and [braintree](https://articles.braintreepayments.com/) merchant account credentials), and drop the "sample_" prefix from the filename

 - Download the gcp sdk then: ```$gcloud app deploy```

Future Development:
Password reset and forgot password functionality
