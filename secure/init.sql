-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables

-- CREATE TABLE `examples` (
-- 	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
-- 	`name`	TEXT NOT NULL
-- );

-- Images Table
CREATE TABLE images (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    name TEXT NOT NULL,
    extension TEXT NOT NULL,
    description TEXT,
    source TEXT NOT NULL,
    link TEXT
);

-- Tags Table
CREATE TABLE tags (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    name TEXT NOT NULL UNIQUE
);

-- Image_Tags Table
CREATE TABLE image_tags (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    image_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL
);

-- TODO: initial seed data

-- INSERT INTO `examples` (id,name) VALUES (1, 'example-1');
-- INSERT INTO `examples` (id,name) VALUES (2, 'example-2');

-- Source: (original work) Dayoon Kim
INSERT INTO images (id, name, extension, description, source, link) VALUES (1, 'Side Dish', 'jpg', 'Side dish, also known as Banchan, is always served as small dishes along with the cooked rice. Banchan is usually served in the middle of the table. They are generally Kimchi or Namul.', '(original work) Dayoon Kim', NULL);

-- Source: (original work) Dayoon Kim
INSERT INTO images (id, name, extension, description, source, link) VALUES (2, 'Budae Jjigae', 'jpg', 'Budae Jjigae is a spicy stew with kimchi, pork, ham, sausage, tofu, and cheese. The dish is popular as an anju, which is accompanied with alcohol drinks.', '(original work) Dayoon Kim', NULL);

-- Source: (original work) Dayoon Kim
INSERT INTO images (id, name, extension, description, source, link) VALUES (3, 'Bulgogi', 'jpg', 'Bulgogi is thinly sliced beef tenderlion marinated in soy sauce. Bulgogi is a very popular dish that can be found anywhere in Korean restaurants!', '(original work) Dayoon Kim', NULL);

-- Source: (original work) Dayoon Kim
INSERT INTO images (id, name, extension, description, source, link) VALUES (4, 'Dduk Mandoo Gook', 'jpg', 'Dduk Mandoo Gook is a beef dumpling soup with thinly sliced rice cake.', '(original work) Dayoon Kim', NULL);

-- Source: (original work) Dayoon Kim
INSERT INTO images (id, name, extension, description, source, link) VALUES (5, 'Pajun', 'jpg', 'Haemul Pajun is a fried batter with mixed seafood and scallions', '(original work) Dayoon Kim', NULL);

-- Source: (original work) Dayoon Kim
INSERT INTO images (id, name, extension, description, source, link) VALUES (6, 'Tangsooyuk', 'jpg', 'Tangsooyuk is fried meat in sour sauce, mixed vegetables.', '(original work) Dayoon Kim', NULL);

-- Source: https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=TCIxLq337dHz2hJnCl6AZg
INSERT INTO images (id, name, extension, description, source, link) VALUES (7, 'Champong', 'jpg', 'Cham Pong is a spicy soup with noodles, seafood, and vegetables.', 'Yelp', 'https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=TCIxLq337dHz2hJnCl6AZg');

-- Source: https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=C-d5nR93GRs403IdsXIECA
INSERT INTO images (id, name, extension, description, source, link) VALUES (8, 'Jajangmyun', 'jpg', 'Jajangmyun is thick noodles with black soybean sauce and vegetables.', 'Yelp', 'https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=C-d5nR93GRs403IdsXIECA');

-- Source: https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=mnHNE_ZxVn7W0tMxMlQ9RQ
INSERT INTO images (id, name, extension, description, source, link) VALUES (9, 'Japchae', 'jpg', 'Japchae is noodles with vegetables. You can add shrimp or chicken for an extra $1 and beef for $2! ', 'Yelp', 'https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=mnHNE_ZxVn7W0tMxMlQ9RQ');

-- Source: https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=uQ_Kgz5J8bJZB192UD8ipA
INSERT INTO images (id, name, extension, description, source, link) VALUES (10, 'Chicken', 'jpg', NULL, 'Yelp', 'https://www.yelp.com/biz_photos/koko-korean-restaurant-ithaca?select=uQ_Kgz5J8bJZB192UD8ipA');


INSERT INTO tags (id, name) VALUES (1, 'Side');
INSERT INTO tags (id, name) VALUES (2, 'Main');
INSERT INTO tags (id, name) VALUES (3, 'Soup');
INSERT INTO tags (id, name) VALUES (4, 'Noodle');
INSERT INTO tags (id, name) VALUES (5, 'Meat');
INSERT INTO tags (id, name) VALUES (6, 'Spicy');
INSERT INTO tags (id, name) VALUES (7, 'Sweet');
INSERT INTO tags (id, name) VALUES (8, 'Seafood');



INSERT INTO image_tags (id, image_id, tag_id) VALUES (1, 1, 1);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (2, 2, 2);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (3, 2, 3);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (4, 2, 4);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (5, 2, 6);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (6, 3, 2);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (7, 3, 5);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (8, 4, 2);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (9, 4, 3);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (10, 4, 5);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (11, 5, 1);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (12, 6, 5);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (13, 6, 7);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (14, 7, 2);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (15, 7, 3);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (16, 7, 4);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (17, 7, 6);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (18, 7, 8);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (19, 8, 4);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (20, 8, 7);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (21, 9, 4);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (22, 9, 5);
INSERT INTO image_tags (id, image_id, tag_id) VALUES (23, 10, 5);


COMMIT;
