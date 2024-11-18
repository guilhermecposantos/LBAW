CREATE SCHEMA IF NOT EXISTS lbaw2335;

SET search_path TO lbaw2335;

DROP TABLE IF EXISTS addToFavorites CASCADE;
DROP TABLE IF EXISTS reportResponse CASCADE;
DROP TABLE IF EXISTS report CASCADE;
DROP TABLE IF EXISTS attends CASCADE;
DROP TABLE IF EXISTS join_request CASCADE;
DROP TABLE IF EXISTS ticket CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS pollVote CASCADE;
DROP TABLE IF EXISTS pollOption CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS topic CASCADE;
DROP TABLE IF EXISTS event CASCADE;
DROP TABLE IF EXISTS users CASCADE;

DROP TYPE IF EXISTS notification_type;
DROP TYPE IF EXISTS event_status;

CREATE TYPE notification_type AS ENUM ('event_is_starting', 'event_started', 'event_ended', 'event_cancelled', 'event_recommendation', 'request_to_join', 'invitation_to_join', 'request_response');
CREATE TYPE event_status AS ENUM ('future', 'ongoing', 'closed');

CREATE TABLE lbaw2335.users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    activeSince TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    isAdmin BOOL ,
    remember_token VARCHAR,
    isbanned BOOL DEFAULT FALSE,
    isdeleted BOOL DEFAULT FALSE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE lbaw2335.topic (
    id SERIAL PRIMARY KEY,
    title VARCHAR(1000) NOT NULL
);
CREATE TABLE lbaw2335.event (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(2000) NOT NULL,
    eventDateTime TIMESTAMP NOT NULL,
    location VARCHAR(255) NOT NULL,
    organizerId INT NOT NULL,
    url VARCHAR(255) UNIQUE,
    topicId INT NOT NULL,
    startSales TIMESTAMP,
    endSales TIMESTAMP,
    isPublic BOOL NOT NULL,
    availableTickets INT,
    totalTickets INT,
    ticketprice MONEY NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status event_status DEFAULT 'future',
    CONSTRAINT fk_event_user FOREIGN KEY (organizerId) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_event_topic FOREIGN KEY (topicId) REFERENCES topic(id) ON UPDATE CASCADE,
    CONSTRAINT coherent_dates CHECK (startSales < endSales),
    CONSTRAINT coherent_tickets CHECK (availableTickets >= 0)
);
CREATE TABLE lbaw2335.comment (
    id SERIAL PRIMARY KEY,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    content VARCHAR(2000) NOT NULL,
    isPoll BOOL DEFAULT FALSE,
    CONSTRAINT fk_comment_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_comment_event FOREIGN KEY (event_id) REFERENCES event(id) ON UPDATE CASCADE
);
CREATE TABLE lbaw2335.polloption (
    id SERIAL PRIMARY KEY,
    comment_id INT NOT NULL,
    option VARCHAR(255) NOT NULL,
    CONSTRAINT fk_pollOption_comment FOREIGN KEY (comment_id) REFERENCES comment(id) ON UPDATE CASCADE
);
CREATE TABLE lbaw2335.pollvote (
    id SERIAL PRIMARY KEY,
    option_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT fk_pollVote_option FOREIGN KEY (option_id) REFERENCES pollOption(id) ON UPDATE CASCADE,
    CONSTRAINT fk_pollVote_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE
);
CREATE TABLE lbaw2335.notification (
    id SERIAL PRIMARY KEY,
    description VARCHAR(1000) NOT NULL,
    user1Id INT,
    user2Id INT,
    eventId INT,
    dateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notification_type notification_type NOT NULL,
    CONSTRAINT fk_notification_user1 FOREIGN KEY (user1Id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_notification_user2 FOREIGN KEY (user2Id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_notification_event FOREIGN KEY (eventId) REFERENCES event(id) ON UPDATE CASCADE
);
CREATE TABLE lbaw2335.ticket (
    id SERIAL PRIMARY KEY,
    eventId INT NOT NULL,
    userId INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    price MONEY NOT NULL,
    paymentMethod VARCHAR(250) NOT NULL,
    CONSTRAINT fk_ticket_event FOREIGN KEY (eventId) REFERENCES event(id) ON UPDATE CASCADE,
    CONSTRAINT fk_ticket_user FOREIGN KEY (userId) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT coherent_price CHECK (price >= '0'::MONEY)
);
CREATE TABLE lbaw2335.report (
    id SERIAL PRIMARY KEY,
    userId INT NOT NULL,
    reportedUserId INT,
    reportedEventId INT,
    dateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reportauthor FOREIGN KEY (userId) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_reporteduser FOREIGN KEY (reportedUserId) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_reportedevent FOREIGN KEY (reportedEventId) REFERENCES event(id) ON UPDATE CASCADE
);

---------------
-- RELATIONS --
---------------

CREATE TABLE lbaw2335.attends (
    user_id INT,
    event_id INT,
    PRIMARY KEY (user_id, event_id),
    CONSTRAINT fk_attends_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_attends_event FOREIGN KEY (event_id) REFERENCES event(id) ON UPDATE CASCADE
);

CREATE TABLE lbaw2335.join_request (
    user_id INT,
    event_id INT,
    approved BOOL DEFAULT FALSE,
    PRIMARY KEY (user_id, event_id),
    CONSTRAINT fk_join_request_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_join_request_event FOREIGN KEY (event_id) REFERENCES event(id) ON UPDATE CASCADE
);
CREATE TABLE lbaw2335.reportResponse (
    adminId INT NOT NULL,
    reportId INT NOT NULL,
    dateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    accepted BOOL NOT NULL,
    PRIMARY KEY (adminId, reportId),
    CONSTRAINT fk_reportadmin FOREIGN KEY (adminId) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_reportid FOREIGN KEY (reportId) REFERENCES report(id) ON UPDATE CASCADE
);
CREATE TABLE lbaw2335.addToFavorites (
    userId INT NOT NULL,
    eventId INT NOT NULL,
    PRIMARY KEY (userId, eventId),
    CONSTRAINT fk_addtofavorites_user FOREIGN KEY (userId) REFERENCES users(id) ON UPDATE CASCADE,
    CONSTRAINT fk_addtofavorites_event FOREIGN KEY (eventId) REFERENCES event(id) ON UPDATE CASCADE
);

----------------
--- TRIGGERS ---
----------------

CREATE OR REPLACE FUNCTION verify_email()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.email LIKE '%@%.%' THEN
        RETURN NEW;
    ELSE
        RETURN NULL;
    END IF;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER validate_email
BEFORE INSERT ON users
FOR EACH ROW
EXECUTE FUNCTION verify_email();



CREATE OR REPLACE FUNCTION change_vote()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM pollVote
    WHERE user_id = NEW.user_id
    AND option_id IN (SELECT id FROM pollOption 
    WHERE comment_id = (SELECT comment_id FROM pollOption WHERE id = NEW.option_id));
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER change_vote_trigger
BEFORE INSERT ON pollVote
FOR EACH ROW
EXECUTE FUNCTION change_vote();

----------------
--- INDEXES ----
----------------

CREATE INDEX idx_addToFavorites_userId ON addToFavorites (userId);
CREATE INDEX idx_attends_eventId ON attends (event_id);
CREATE INDEX idx_event_eventDateTime ON event (eventDateTime);


------------------
-- reset serial --
------------------

SELECT setval('users_id_seq', 1, false);
SELECT setval('topic_id_seq', 1, false);
SELECT setval('event_id_seq', 1, false);
SELECT setval('comment_id_seq', 1, false);
SELECT setval('pollOption_id_seq', 1, false);
SELECT setval('pollVote_id_seq', 1, false);
SELECT setval('notification_id_seq', 1, false);
SELECT setval('ticket_id_seq', 1, false);
SELECT setval('report_id_seq', 1, false);


INSERT INTO users (username, email, password, firstName, lastName, isAdmin) VALUES
('username1', 'user1@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','1',TRUE),
('username2', 'user2@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','2',FALSE),
('username3', 'user3@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','3',FALSE),
('invaliduser', 'emailemail', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','1',TRUE),
('username4', 'user4@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','4',FALSE),
('username5', 'user5@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','5',FALSE),
('username6', 'user6@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','6',FALSE),
('username7', 'user7@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','7',FALSE),
('invaliduser2', 'useemailcom', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','1',TRUE),
('username8', 'user8@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','8',FALSE),
('username9', 'user9@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','9',TRUE),
('username10', 'user10@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','10',TRUE),
('username11', 'user11@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','11',TRUE),
('username12', 'user12@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','12',TRUE),
('username13', 'user13@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','13',TRUE),
('username14', 'user14@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','14',TRUE),
('username15', 'user15@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','15',FALSE),
('username16', 'user16@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','16',FALSE),
('username17', 'user17@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','17',FALSE),
('username18', 'user18@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','18',FALSE),
('username19', 'user19@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','19',FALSE),
('username20', 'user20@email.com', '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W', 'User','20',FALSE);

INSERT INTO topic (title)
VALUES
    ('Music Concerts'),
    ('Sports Events'),
    ('Art Exhibitions'),
    ('Tech Conferences'),
    ('Food Festivals');

INSERT INTO event (title, description, eventDateTime, location, organizerId, url, topicId, startSales, endSales, isPublic, availableTickets, totalTickets, ticketprice)
VALUES
    ('Art Workshop - Painting in Nature', 'Join our painting workshop in a beautiful natural setting and unleash your creativity.', '2024-11-06 10:00:00', 'Nature Park', 6, 'art-workshop', 3, NULL, NULL, TRUE, 100, 100, 50.00),
    ('Tech Webinar - AI Trends', 'Explore the latest trends in artificial intelligence during this informative webinar.', '2024-11-07 15:00:00', 'Online', 7, 'ai-webinar', 4, '2024-10-07 15:00:00', '2024-11-07 17:00:00', TRUE, 150, 150, 35.00),
    ('Fashion Show - Spring Collection', 'Experience the fashion world with a dazzling display of spring fashion collections.', '2024-11-08 19:30:00', 'Fashion Center', 8, 'spring-fashion', 3, '2024-10-08 19:30:00', '2024-11-08 21:30:00', TRUE, 200, 200, 25.00),
    ('Movie Night - Classic Film Screening', 'Enjoy a night of classic cinema with a screening of all-time favorite films.', '2024-11-09 19:00:00', 'Cinema House', 6, 'movie-night', 1, NULL, NULL, TRUE, 150, 150, 40.00),
    ('Charity Gala - Fundraising Event', 'Support a good cause at our charity gala featuring entertainment and fundraising activities.', '2024-11-10 18:30:00', 'Grand Ballroom', 10, 'charity-gala', 5, NULL, NULL, FALSE, 100, 100, 20.00),
    ('Art Exhibition - Abstract Art Showcase', 'Dive into the world of abstract art with a unique collection of abstract paintings.', '2024-11-11 10:00:00', 'Art Gallery', 11, 'abstract-art-show', 3, NULL, NULL, TRUE, 100, 100, 30.00),
    ('Tech Conference - Cybersecurity Summit', 'Learn about the latest trends and practices in cybersecurity at our summit.', '2024-11-12 09:00:00', 'Tech Center', 12, 'cybersecurity-summit', 4, '2024-10-12 09:00:00', '2024-11-12 17:00:00', FALSE, 250, 250,15.00),
    ('Food Festival - Street Food Extravaganza', 'Indulge in a variety of street foods from different corners of the world.', '2024-11-13 17:00:00', 'Street Market', 13, 'street-food-fest', 5, NULL, NULL, TRUE, 100, 100, 60.00),
    ('Live Music Performance - Jazz Night', 'Enjoy a relaxing evening with live jazz music and soothing tunes.', '2024-11-14 20:00:00', 'Jazz Club', 14, 'jazz-night', 1, '2024-10-14 20:00:00', '2024-11-14 22:00:00', TRUE, 100, 100, 45.00),
    ('Sports Event - Tennis Tournament', 'Watch thrilling matches in our annual tennis tournament featuring top athletes.', '2024-11-15 12:00:00', 'Tennis Arena', 15, 'tennis-tournament', 2, NULL, NULL, FALSE, 200, 200, 55.00);

INSERT INTO comment (user_id, event_id, content, isPoll)
VALUES
    (1, 1, 'This event looks amazing!', FALSE),
    (3, 2, 'Great lineup of teams for the championship.', FALSE),
    (5, 3, 'The art exhibition was stunning.', FALSE),
    (6, 3, 'I was truly inspired by the art.', FALSE),
    (7, 4, 'Excited to learn about the latest tech trends!', FALSE),
    (10, 5, 'I tried so many delicious dishes.', FALSE),
    (11, 6, 'Abstract art is my favorite!', FALSE),
    (12, 6, 'The paintings were truly unique.', FALSE),
    (13, 7, 'The cybersecurity summit was informative.', FALSE),
    (14, 7, 'I learned a lot about cybersecurity.', FALSE),
    (16, 8, 'I indulged in so many treats.', FALSE),
    (17, 9, 'Jazz music is so soothing.', FALSE),
    (18, 9, 'I had a great time at the jazz night.', FALSE),
    (19, 10, 'Tennis tournament was thrilling!', FALSE),
    (20, 10, 'Who deserved to win?', TRUE);

INSERT INTO pollOption (comment_id, option) VALUES
(15, 'Djokovic'),
(15, 'Alcaraz');

INSERT INTO pollVote (option_id, user_id) VALUES
(1, 1),
(2, 2),
(1, 3),
(2, 3),
(2, 6),
(1, 5);

INSERT INTO notification (description, user1Id, user2Id, eventId, notification_type)
VALUES
    ('The event is starting soon!', NULL, 2, 1, 'event_is_starting'),
    ('The event has started. Enjoy!', NULL, 1, 1, 'event_started'),
    ('The event has ended. Thanks for participating!', NULL, 2, 1, 'event_ended'),
    ('The event has been cancelled. We apologize for any inconvenience.', NULL, 3, 2, 'event_cancelled'),
    ('You have a new event recommendation. Check it out!', 5, 3, 3, 'event_recommendation');

INSERT INTO ticket (eventId, userId, price, paymentMethod)
VALUES
    (1, 1, 50.00, 'Credit Card'),
    (1, 2, 50.00, 'PayPal'),
    (2, 3, 35.00, 'Credit Card'),
    (2, 10, 35.00, 'PayPal'),
    (3, 5, 25.00, 'Credit Card'),
    (3, 6, 25.00, 'PayPal'),
    (4, 7, 40.00, 'Credit Card'),
    (4, 8, 40.00, 'PayPal'),
    (5, 13, 20.00, 'Credit Card'),
    (5, 10, 20.00, 'PayPal'),
    (6, 11, 30.00, 'Credit Card'),
    (6, 12, 30.00, 'PayPal'),
    (7, 13, 15.00, 'Credit Card'),
    (7, 14, 15.00, 'PayPal'),
    (8, 15, 60.00, 'Credit Card'),
    (8, 16, 60.00, 'PayPal'),
    (9, 17, 45.00, 'Credit Card'),
    (9, 18, 45.00, 'PayPal'),
    (10, 19, 55.00, 'Credit Card'),
    (10, 20, 55.00, 'PayPal');

INSERT INTO report (userId, reportedUserId, reportedEventId)
VALUES
    (1, 2, NULL),
    (3, NULL, 2),
    (5, NULL, 3);

INSERT INTO attends (user_id, event_id)
VALUES
    (1, 1),
    (2, 1),
    (3, 2),
    (10, 2),
    (5, 3),
    (6, 3),
    (7, 4),
    (8, 4),
    (13, 5),
    (10, 5),
    (11, 6),
    (12, 6),
    (13, 7),
    (14, 7),
    (15, 8),
    (16, 8),
    (17, 9),
    (18, 9),
    (19, 10),
    (20, 10);

INSERT INTO reportResponse (adminId, reportId, accepted) VALUES
(1, 1, TRUE),
(1, 2, FALSE),
(1, 3, TRUE);

INSERT INTO addToFavorites (userId, eventId)
VALUES
    (1, 1),
    (1, 2),
    (2, 3),
    (2, 4),
    (3, 5),
    (3, 6),
    (5, 9),
    (5, 10);