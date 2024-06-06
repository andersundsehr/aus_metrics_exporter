# What does it do?
the extension provides a collector to collect metrics and an endpoint to display them in Prometheus compatible format.

# Cache
A cache with the name ausmetricsexporter_cache is created.
The SimpleFileBackend is used for compatibility, but if you want to collect metrics at high frequency, or if you want to speed up the collection, I recommend using an appropriate cache backend such as Redis, or APCu if only a runtime environment such as PHP-FPM is used. 

# Example usage

Define the data endpoint in your site configuration:

```YAML
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    map:
      metrics.txt: 1717676395
```

# Notes
Remember that the metrics could contain sensitive data and should be protected from the public.
