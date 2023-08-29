from faker import Faker
import pymysql
import random
from datetime import datetime
import logging

logging.basicConfig(encoding='utf-8', level=logging.WARNING)


class Print_answer:

    connection = None
    cursor = None
    fake = None
    order_max = 2000
    bnb_max = 100

    def __init__(self):
        self.fake = Faker()
        pass

    def db_conn(self):
        try:
            self.connection = pymysql.connect(
                host='localhost',
                port=3306,
                user='root',
                password='your_password',
                database='AsiaYo',
                charset='utf8'
            )
            self.cursor = self.connection.cursor()
            return True
        except pymysql.MySQLError as e:
            print("Connection error:", str(e))
            return False

    def db_init(self):
        if not self.db_conn():
            return None
        try:
            with open('mysql.sql', 'r') as file:
                sql_commands = file.read()

            commands = sql_commands.split(';')

            for command in commands:
                if command.strip():
                    self.cursor.execute(command)

            self.connection.commit()

        except Exception as e:
            self.connection.rollback()
            print("An error occurred:", str(e))
            return None
        else:
            return True

        finally:
            self.cursor.close()
            self.connection.close()
            return None

    def insert_fake_db(self):
        if not self.db_conn():
            return None
        bnb_count = 0
        try:
            while bnb_count < self.bnb_max:
                bnbs_query = "INSERT INTO bnbs (name) VALUES (%s)"
                self.cursor.execute(bnbs_query, (self.fake.company()))
                logging.debug(f'INSERT bnbs id: {self.cursor.lastrowid}')

                bnb_id = self.cursor.lastrowid
                room_count = 0
                room_max = random.randint(1, 5)

                while room_count < room_max:
                    rooms_query = "INSERT INTO rooms (bnb_id, name) VALUES (%s, %s)"
                    self.cursor.execute(
                        rooms_query, (bnb_id, self.fake.company()))
                    logging.debug(f'INSERT rooms id: {self.cursor.lastrowid}')

                    room_count += 1
                bnb_count += 1

            self.connection.commit()

            select_query = f'SELECT id FROM rooms ORDER BY RAND() LIMIT {self.order_max}'
            self.cursor.execute(select_query)
            random_rooms = self.cursor.fetchall()

            start_date = datetime(2023, 4, 1)
            end_date = datetime(2023, 5, 31)

            for room_id in random_rooms:
                room_id = room_id[0]
                select_query = f'SELECT bnb_id FROM rooms WHERE id="{room_id}"'
                self.cursor.execute(select_query)
                random_room = self.cursor.fetchone()
                bnb_id = random_room[0]
                check_in_date = self.fake.date_time_between_dates(
                    datetime_start=start_date, datetime_end=end_date)
                check_out_date = self.fake.date_time_between_dates(
                    datetime_start=check_in_date, datetime_end=end_date)
                amount = random.randint(2000, 7000)
                currency = 'TWD'

                order_query = "INSERT INTO orders ( \
                                    bnb_id, \
                                    room_id, \
                                    currency, \
                                    amount, \
                                    check_in_date, \
                                    check_out_date \
                                ) VALUES (%s, %s, %s, %s, %s, %s)"
                self.cursor.execute(order_query, (
                    bnb_id,
                    room_id,
                    currency,
                    amount,
                    check_in_date,
                    check_out_date
                ))

                logging.debug(f'INSERT orders id: {self.cursor.lastrowid}')

            self.connection.commit()
        except Exception as e:
            self.connection.rollback()
            print("An error occurred:", str(e))
            return None
        else:
            return True

        finally:
            self.cursor.close()
            self.connection.close()
            return None

    def closs_db(self):
        return self.connection.close()

    def answer(self):
        if not self.db_conn():
            return None
        try:
            with open('answer.sql', 'r') as file:
                sql_commands = file.read()

                if sql_commands.strip():
                    self.cursor.execute(sql_commands)

                    random_room = self.cursor.fetchall()
                    self.answer_to_table(random_room)

            self.connection.commit()

        except Exception as e:
            self.connection.rollback()
            print("An error occurred:", str(e))
            return None
        else:
            return True

        finally:
            self.cursor.close()
            self.connection.close()
            return None

    def sql_nswer(self):
        with open('answer.sql', 'r') as file:
            sql_commands = file.read()
            print("------------------SQL-------------------")
            print(sql_commands)

    def answer_to_table(self, answer):
        print("------------------Table------------------")
        print("{:<8} {:<20} {:<10}".format(
            'bnb_id', 'bnb_name', 'may_amount'))
        for v in answer:
            bnb_id, bnb_name, may_amount = v

            print("{:<8} {:<20} {:<10}".format(
                bnb_id, bnb_name, may_amount))


if __name__ == '__main__':

    print_answer = Print_answer()
    print_answer.db_init()
    print_answer.insert_fake_db()
    print_answer.sql_nswer()
    print_answer.answer()
