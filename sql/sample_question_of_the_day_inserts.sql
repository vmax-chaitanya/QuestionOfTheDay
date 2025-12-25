-- Sample data: 20 questions for question_of_the_day
-- Mix of past and future dates, 2–4 options, general/common questions

INSERT INTO `question_of_the_day` (
  `question_description`,
  `category`,
  `question_date`,
  `status`,
  `option_1`,
  `option_2`,
  `option_3`,
  `option_4`,
  `correct_answer`,
  `explanation`
) VALUES
-- 1 (3 days ago, 4 options)
('What is the capital of India?',
 'General Knowledge',
 DATE_SUB(CURDATE(), INTERVAL 3 DAY),
 'active',
 'New Delhi',
 'Mumbai',
 'Kolkata',
 'Chennai',
 1,
 'New Delhi is the capital city of India.'
),

-- 2 (2 days ago, 3 options)
('How many days are there in a leap year?',
 'General Knowledge',
 DATE_SUB(CURDATE(), INTERVAL 2 DAY),
 'active',
 '365',
 '366',
 '367',
 NULL,
 2,
 'A leap year has 366 days.'
),

-- 3 (yesterday, 2 options)
('Which planet is known as the Red Planet?',
 'General Knowledge',
 DATE_SUB(CURDATE(), INTERVAL 1 DAY),
 'active',
 'Mars',
 'Jupiter',
 NULL,
 NULL,
 1,
 'Mars appears red due to iron oxide on its surface.'
),

-- 4 (today, 4 options)
('Which is the largest mammal on Earth?',
 'General Knowledge',
 CURDATE(),
 'active',
 'African Elephant',
 'Blue Whale',
 'Giraffe',
 'Hippopotamus',
 2,
 'The blue whale is the largest mammal on Earth.'
),

-- 5 (today, 3 options)
('Which gas do plants absorb from the atmosphere?',
 'General Knowledge',
 CURDATE(),
 'active',
 'Oxygen',
 'Carbon Dioxide',
 'Nitrogen',
 NULL,
 2,
 'Plants absorb carbon dioxide for photosynthesis.'
),

-- 6 (tomorrow, 2 options)
('How many continents are there on Earth?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 1 DAY),
 'active',
 '6',
 '7',
 NULL,
 NULL,
 2,
 'Most commonly, 7 continents are recognized.'
),

-- 7 (+2 days, 4 options)
('Which is the smallest prime number?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 2 DAY),
 'active',
 '0',
 '1',
 '2',
 '3',
 3,
 '2 is the smallest and the only even prime number.'
),

-- 8 (+3 days, 3 options)
('Which instrument is used to measure temperature?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 3 DAY),
 'active',
 'Barometer',
 'Thermometer',
 'Hygrometer',
 NULL,
 2,
 'A thermometer is used to measure temperature.'
),

-- 9 (+4 days, 2 options)
('Which is the largest ocean on Earth?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 4 DAY),
 'active',
 'Pacific Ocean',
 'Atlantic Ocean',
 NULL,
 NULL,
 1,
 'The Pacific Ocean is the largest and deepest ocean.'
),

-- 10 (+5 days, 4 options)
('What is the chemical symbol for water?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 5 DAY),
 'active',
 'O2',
 'H2O',
 'CO2',
 'HO2',
 2,
 'The chemical formula for water is H2O.'
),

-- 11 (+6 days, 3 options)
('Which planet is closest to the Sun?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 6 DAY),
 'active',
 'Mercury',
 'Venus',
 'Earth',
 NULL,
 1,
 'Mercury is the closest planet to the Sun.'
),

-- 12 (+7 days, 2 options)
('Which is the longest river in the world?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 7 DAY),
 'active',
 'Nile',
 'Amazon',
 NULL,
 NULL,
 1,
 'The Nile is traditionally considered the longest river in the world.'
),

-- 13 (+8 days, 4 options)
('What is the boiling point of water at sea level?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 8 DAY),
 'active',
 '50°C',
 '75°C',
 '90°C',
 '100°C',
 4,
 'At sea level, water boils at 100°C.'
),

-- 14 (+9 days, 3 options)
('Which organ pumps blood throughout the human body?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 9 DAY),
 'active',
 'Lungs',
 'Heart',
 'Liver',
 NULL,
 2,
 'The heart is responsible for pumping blood throughout the body.'
),

-- 15 (+10 days, 2 options)
('Which metal is commonly used in electrical wires?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 10 DAY),
 'active',
 'Copper',
 'Gold',
 NULL,
 NULL,
 1,
 'Copper is widely used for electrical wiring due to its conductivity.'
),

-- 16 (+11 days, 4 options)
('Which part of the plant conducts photosynthesis?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 11 DAY),
 'active',
 'Roots',
 'Stem',
 'Leaves',
 'Flowers',
 3,
 'Leaves contain chlorophyll and are the main site of photosynthesis.'
),

-- 17 (+12 days, 3 options)
('Which is the fastest land animal?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 12 DAY),
 'active',
 'Cheetah',
 'Lion',
 'Horse',
 NULL,
 1,
 'The cheetah is the fastest land animal.'
),

-- 18 (+13 days, 2 options)
('Which is the largest continent by area?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 13 DAY),
 'active',
 'Asia',
 'Africa',
 NULL,
 NULL,
 1,
 'Asia is the largest continent by land area.'
),

-- 19 (+14 days, 4 options)
('Which gas is most abundant in the Earth''s atmosphere?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 14 DAY),
 'active',
 'Oxygen',
 'Nitrogen',
 'Carbon Dioxide',
 'Hydrogen',
 2,
 'Nitrogen makes up about 78% of the Earth''s atmosphere.'
),

-- 20 (+15 days, 3 options)
('Which direction does the Sun appear to rise from?',
 'General Knowledge',
 DATE_ADD(CURDATE(), INTERVAL 15 DAY),
 'active',
 'North',
 'East',
 'West',
 NULL,
 2,
 'The Sun appears to rise from the east and set in the west.'
);
