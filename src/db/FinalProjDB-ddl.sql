DROP TABLE IF EXISTS Task;

DROP TABLE IF EXISTS Project;

DROP TABLE IF EXISTS Goal;

DROP TABLE IF EXISTS Calendar;

DROP TABLE IF EXISTS User;

CREATE TABLE
    User (
        userID INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(32),
        last_name VARCHAR(32)
    );

CREATE TABLE
    Calendar (
        calendarID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT NOT NULL,
        capacity INT,
        FOREIGN KEY (userID) REFERENCES User (userID) ON DELETE CASCADE
    );

CREATE TABLE
    Goal (
        goalID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT NOT NULL,
        goal_name VARCHAR(128),
        capacity INT,
        priority ENUM ('Low', 'Medium', 'High'),
        is_complete BOOLEAN,
        FOREIGN KEY (userID) REFERENCES User (userID) ON DELETE CASCADE
    );

CREATE TABLE
    Project (
        projectID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT NOT NULL,
        project_name VARCHAR(128),
        start DATE,
        end DATE,
        priority ENUM ('Low', 'Medium', 'High'),
        is_complete BOOLEAN,
        FOREIGN KEY (userID) REFERENCES User (userID) ON DELETE CASCADE
    );

CREATE TABLE
    Task (
        taskID INT AUTO_INCREMENT PRIMARY KEY,
        userID INT NOT NULL,
        goalID INT,
        projectID INT,
        task_name VARCHAR(128),
        start INT,
        end INT,
        priority ENUM ('Low', 'Medium', 'High'),
        is_complete BOOLEAN,
        FOREIGN KEY (userID) REFERENCES User (userID) ON DELETE CASCADE,
        FOREIGN KEY (goalID) REFERENCES Goal (goalID) ON DELETE CASCADE,
        FOREIGN KEY (projectID) REFERENCES Project (projectID) ON DELETE CASCADE
    );

-- Insert into User table
INSERT INTO
    User (first_name, last_name)
VALUES
    ('Alice', 'Johnson'),
    ('Bob', 'Smith'),
    ('Charlie', 'Brown'),
    ('Diana', 'White'),
    ('Edward', 'Black');

-- Insert into Calendar table
INSERT INTO
    Calendar (userID, capacity)
VALUES
    (1, 10),
    (2, 15),
    (3, 12),
    (4, 20),
    (5, 8);

-- Insert into Goal table
INSERT INTO
    Goal (
        userID,
        goal_name,
        capacity,
        priority,
        is_complete
    )
VALUES
    (1, 'Learn SQL', 10, 'High', false),
    (2, 'Complete Project A', 8, 'Medium', false),
    (3, 'Exercise Regularly', 5, 'Low', true),
    (4, 'Save for Vacation', 12, 'High', false),
    (5, 'Read 20 Books', 15, 'Medium', false);

INSERT INTO
    Project (
        userID,
        project_name,
        start,
        end,
        priority,
        is_complete
    )
VALUES
    (
        1,
        'Database Design',
        '2024-01-10',
        '2024-01-20',
        'High',
        false
    ),
    (
        2,
        'Mobile App Development',
        '2024-02-20',
        '2024-02-10',
        'Medium',
        false
    ),
    (
        3,
        'UI/UX Redesign',
        '2024-03-11',
        '2024-03-12',
        'High',
        true
    ),
    (
        4,
        'Marketing Campaign',
        '2024-04-14',
        '2024-04-18',
        'Medium',
        false
    ),
    (
        5,
        'Personal Website',
        '2024-05-05',
        '2024-05-07',
        'Low',
        false
    );

INSERT INTO
    Task (
        userID,
        goalID,
        projectID,
        task_name,
        start,
        end,
        priority,
        is_complete
    )
VALUES
    (
        1,
        1,
        1,
        'Design ER Diagram',
        15,
        25,
        'High',
        false
    ),
    (
        2,
        2,
        2,
        'Develop Login Screen',
        25,
        35,
        'Medium',
        false
    ),
    (3, 3, 3, 'Test User Flows', 35, 45, 'High', true),
    (
        4,
        4,
        4,
        'Launch Campaign',
        45,
        55,
        'Medium',
        false
    ),
    (5, 5, 5, 'Write About Page', 55, 65, 'Low', false);