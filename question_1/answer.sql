SELECT
	bnb.id AS bnb_id,
	bnb.name AS bnb_name,
	SUM(o.amount) AS may_amount
FROM
	orders AS o
LEFT JOIN
	bnbs AS bnb
ON
	bnb.id = o.bnb_id
WHERE
	o.currency = 'TWD'
	AND o.check_in_date >= '2023-05-01'
	AND o.check_out_date <= '2023-05-31'
GROUP BY
	o.bnb_id
ORDER BY
	may_amount DESC
LIMIT 10;