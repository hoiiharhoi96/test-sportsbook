DELIMITER //

DROP PROCEDURE IF EXISTS GetFullEvents //

CREATE PROCEDURE GetFullEvents(
    IN p_sport_id INT,
    IN p_tournament_id INT,
    IN p_event_id INT,
    IN p_limit INT,
    IN p_offset INT,
    IN p_snapshot_id INT,
    IN p_snapshot_time DATETIME
)
BEGIN

select
    JSON_OBJECT(
        'id', e.id,
        'start_time', e.start_time,
        'name', e.name,
        'sport', s.name,
        'country', c.name,
        'tournament', t.name,
        'scopes', (SELECT JSON_ARRAYAGG(JSON_OBJECT(
            'id', sc.id,
            'type', sc.type,
            'number', sc.number,
            'markets', (SELECT JSON_ARRAYAGG(JSON_OBJECT(
                'type', m.type,
                'type_parameter', m.type_parameter,
                'outcomes', (SELECT JSON_ARRAYAGG(JSON_OBJECT(
                    'id', o.id,
                    'odds', o.odds
                ))
                FROM outcomes o WHERE o.market_id = m.id)
            ))
            FROM markets m WHERE m.scope_id = sc.id)
        ))
        FROM scopes sc WHERE sc.event_id = e.id)
    ) data
from events e
inner join tournaments t on e.tournament_id = t.id
inner join sports s on t.sport_id = s.id
inner join countries c on t.country_id = c.id
where (p_sport_id is null or s.id = p_sport_id)
  and (p_tournament_id is null or t.id = p_tournament_id)
  and (p_event_id is null or e.id = p_event_id)
  and (p_snapshot_id is null or e.id <= p_snapshot_id)
  and (p_snapshot_time IS NULL or e.start_time >= p_snapshot_time)
ORDER BY e.start_time, e.id
LIMIT p_limit OFFSET p_offset;

END //

DELIMITER ;
