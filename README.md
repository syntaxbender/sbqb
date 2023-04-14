# sbqb - MySQL Query Builder

no schema checks have been added for performance reasons.

## Select
```
SELECT select_list
FROM table_name
[JOIN table_name ON condition]
WHERE [condition]
GROUP BY column_name
ORDER BY column_name [ASC | DESC]
LIMIT offset, count;
```

## Insert

```
INSERT INTO table_name
SET column1=value1, column2=value2, ..., columnN=valueN;
```

## Delete
```
DELETE FROM table_name
WHERE [condition];
```
## Update
```
UPDATE table_name
SET column1 = value1, column2 = value2, ..., columnN = valueN
WHERE [condition];
```

next release targets
- optional schema checks
- argument field validations
- subquery support.
