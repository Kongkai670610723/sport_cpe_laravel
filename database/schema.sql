-- ====================================================================
-- SPORT.CPE — Master Database Full Setup Script (Enhanced 7-Table Schema)
-- Course: 261342 Database Systems (Term Project)
-- Database: sport_cpe_db
-- Description: Creates 7 production-grade tables including M:N junction table
--              promotion_products, eliminates redundant product flags,
--              enhances promotions with date and usage constraints,
--              supports line-item discounts in order_items,
--              and inserts 128 products, 6 users, 4 promotions, and 12 past orders.
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;

DELETE FROM order_items;
DELETE FROM orders;
DELETE FROM promotion_products;
DELETE FROM products;
DELETE FROM promotions;
DELETE FROM users;
DELETE FROM categories;

-- ====================================================================
-- INSERT DATA
-- ====================================================================

-- 1. Insert Categories
INSERT INTO categories (category_id, category_name, description) VALUES
(1, 'Running', 'รองเท้าวิ่ง เสื้อผ้า นาฬิกา GPS และอุปกรณ์สำหรับนักวิ่งทุกระดับ'),
(2, 'Badminton', 'ไม้แบดมินตัน ลูกขนไก่ เอ็น กริป และกระเป๋าแบดมินตันเกรดแข่งขัน'),
(3, 'Basketball', 'ลูกบาสเกตบอล รองเท้าบาสเกตบอล สนับ และอุปกรณ์ฝึกซ้อมมาตรฐาน FIBA'),
(4, 'Football', 'รองเท้าสตั๊ด ลูกฟุตบอล ถุงมือผู้รักษาประตู และสนับแข้งแข่งขัน'),
(5, 'Swim', 'แว่นตาว่ายน้ำ หมวกว่ายน้ำ ชุดว่ายน้ำแข่งขัน และอุปกรณ์ฝึกซ้อมในน้ำ'),
(6, 'Fitness', 'ดัมเบล บาร์เบล ยางยืด เสื่อโยคะ และอุปกรณ์ยกน้ำหนักเวทเทรนนิ่ง'),
(7, 'Camping', 'เต็นท์ ถุงนอน เก้าอี้สนาม เตาพกพา และอุปกรณ์เดินป่าผจญภัย'),
(8, 'Volleyball', 'ลูกวอลเลย์บอล รองเท้า สนับเข่า และตาข่ายแข่งขันมาตรฐาน FIVB');

-- 2. Insert Users (Admin & Customers)
INSERT INTO users (user_id, name, email, password_hash, phone, address, role) VALUES
(1, 'Kongkai Jai-dee', 'kongkai@cpe.eng.cmu.ac.th', '$2y$10$KongkaiSecureHash12345678901234567890', '089-123-4567', 'ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มช. 50200', 'customer'),
(2, 'Admin SportCPE', 'admin@sportcpe.com', '$2y$10$AdminMasterSportCPE9998887776665554443', '053-942-023', 'สำนักงาน SPORT.CPE อาคาร 30 ปี คณะวิศวกรรมศาสตร์ มช. 50200', 'admin'),
(3, 'Somsak Rattanakul', 'somsak.r@gmail.com', '$2y$10$SomsakPassHash1234567890123456789012', '081-456-7890', '128/4 ถ.ห้วยแก้ว ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'customer'),
(4, 'Arisa Wongsuwan', 'arisa.w@outlook.com', '$2y$10$ArisaPassHash12345678901234567890123', '086-789-1234', '45/2 หมู่ 5 ถ.นิมมานเหมินท์ ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'customer'),
(5, 'Thanawat Siriwat', 'thanawat.s@cmu.ac.th', '$2y$10$ThanawatPassHash123456789012345678901', '092-345-6789', 'หอพักนักศึกษาชาย อาคาร 4 มหาวิทยาลัยเชียงใหม่ 50200', 'customer'),
(6, 'Ploypailin Chaisri', 'ploypailin.c@yahoo.com', '$2y$10$PloyPassHash123456789012345678901234', '084-567-8901', '89 ถ.คลองชลประทาน ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'customer');

-- 3. Insert Promotions (Aligned with Laravel Schema)
INSERT INTO promotions (promo_id, promo_code, description, discount_type, discount_value, min_order_amount, start_date, end_date, is_active) VALUES
(1, 'SAVE15', 'คูปองส่วนลดพิเศษ 15%', 'PERCENT', 15.00, 1000.00, '2026-10-01', '2026-12-31', 1),
(2, 'PERKS20', 'คูปองสิทธิพิเศษนักกีฬา 20%', 'PERCENT', 20.00, 2000.00, '2026-10-01', '2026-12-31', 1),
(3, 'BUY2GET1', 'โปรโมชันซื้อ 2 แถม 1 อัตโนมัติ (แบดมินตัน)', 'BOGO', 0.00, 0.00, '2026-10-01', '2026-12-31', 1),
(4, 'SPORT10', 'คูปองส่วนลดต้อนรับสมาชิก 10%', 'PERCENT', 10.00, 500.00, '2026-10-01', '2026-12-31', 1);

-- 4. Insert Products (128 Items: 16 per Category - Clean Studio Assets)
INSERT INTO products (product_id, category_id, name, brand, description, price, stock_quantity, image_url) VALUES
(1, 1, 'Nike Air Zoom Pegasus 40', 'Nike', 'รองเท้าวิ่งถนนยอดนิยม นุ่ม เด้ง ตอบสนองดีเยี่ยมทุกระยะ', 4600.00, 25, '/images/figma/prod_pegasus_40.png'),
(2, 1, 'Nike Vaporfly 3', 'Nike', 'รองเท้าวิ่งแข่งระดับแนวหน้า แผ่นคาร์บอน Flyplate เพื่อการทำความเร็วสูงสุด', 8200.00, 10, '/images/figma/prod_pegasus_40.png'),
(3, 1, 'Adidas Adizero Adios Pro 3', 'Adidas', 'รองเท้าวิ่งมาราธอนระดับท็อป เทคโนโลยี Energyrods คืนพลังงานก้าวต่อก้าว', 7900.00, 12, '/images/figma/prod_pegasus_40.png'),
(4, 1, 'Adidas Ultraboost Light', 'Adidas', 'รองเท้าวิ่งพื้น Boost เบาขึ้น 30% ซัพพอร์ตการวิ่งและใส่สบายตลอดวัน', 6500.00, 20, '/images/figma/prod_pegasus_40.png'),
(5, 1, 'Asics Novablast 4', 'Asics', 'โฟม FF BLAST PLUS ECO นุ่มเด้งเป็นเอกลักษณ์ น้ำหนักเบา', 4900.00, 18, '/images/figma/prod_pegasus_40.png'),
(6, 1, 'Asics Gel-Kayano 30', 'Asics', 'รองเท้าวิ่ง Stability ซัพพอร์ตเท้าล้ม เทคโนโลยี 4D GUIDANCE SYSTEM', 6200.00, 15, '/images/figma/prod_pegasus_40.png'),
(7, 1, 'Hoka Clifton 9', 'Hoka', 'รองเท้าวิ่งรองรับแรงกระแทกสูงสุด โฟมหนานุ่ม น้ำหนักเบาเป็นพิเศษ', 5490.00, 22, '/images/figma/prod_pegasus_40.png'),
(8, 1, 'Saucony Endorphin Speed 3', 'Saucony', 'แผ่นไนลอนแบบมีปีก โฟม PWRRUN PB วิ่งเร็วได้ทั้งซ้อมและแข่ง', 5990.00, 14, '/images/figma/prod_pegasus_40.png'),
(9, 1, 'Garmin Forerunner 265', 'Garmin', 'นาฬิกาวิ่งจอ AMOLED วัดชีพจร VO2 Max วิเคราะห์ความพร้อมการซ้อม', 16500.00, 8, '/images/figma/prod_coros_pace3.png'),
(10, 1, 'Garmin Forerunner 965', 'Garmin', 'นาฬิกาวิ่งไตรกีฬาขอบไทเทเนียม จอ AMOLED พร้อมแผนที่สีออฟไลน์', 23990.00, 5, '/images/figma/prod_coros_pace3.png'),
(11, 1, 'Coros Pace 3', 'Coros', 'นาฬิกาวิ่งน้ำหนักเบาเพียง 30 กรัม แบตเตอรี่อึดยาวนาน 38 ชั่วโมง', 8900.00, 16, '/images/figma/prod_coros_pace3.png'),
(12, 1, 'FlipBelt Classic Running Belt', 'FlipBelt', 'กระเป๋าคาดเอววิ่งรอบทิศทาง ไม่เด้ง ไม่แกว่ง ใส่โทรศัพท์และขวดน้ำได้', 1390.00, 35, '/images/figma/prod_pegasus_40.png'),
(13, 1, 'Compressport R2 Oxygen Calf Sleeves', 'Compressport', 'ปลอกขารัดน่องระดับการแพทย์ ลดแรงสั่นสะเทือน ป้องกันตะคริว', 1950.00, 24, '/images/figma/prod_pegasus_40.png'),
(14, 1, 'Nike Dri-FIT Aerobill Running Cap', 'Nike', 'หมวกวิ่งระบายอากาศ ป้องกันรังสี UV แห้งไวด้วยเทคโนโลยี Dri-FIT', 850.00, 40, '/images/figma/prod_pegasus_40.png'),
(15, 1, 'CamelBak Ultra Pro Running Vest (7L)', 'CamelBak', 'เป้น้ำวิ่งเทรลและอัลตร้ามาราธอน พร้อมขวดน้ำ Quick Stow 500ml 2 ขวด', 4290.00, 12, '/images/figma/prod_pegasus_40.png'),
(16, 1, 'GU Energy Gel Mixed Pack (Box of 24)', 'GU', 'เจลพลังงานรวมรสชาติ ให้คาร์โบไฮเดรต กรดอะมิโน และอิเล็กโทรไลต์', 1800.00, 30, '/images/figma/prod_pegasus_40.png'),
(17, 2, 'Yonex Astrox 99 Pro Racket', 'Yonex', 'ไม้แบดมินตันระดับท็อป ก้านแข็ง หัวหนัก พลังตบทำลายล้างสูง', 6900.00, 15, '/images/figma/prod_astrox_vertical.png'),
(18, 2, 'Yonex Nanoflare 1000Z Racket', 'Yonex', 'ไม้แบดมินตันสายความเร็ว สวิงไว ตีลูกตัดหยอดและตอบสนองเร็วที่สุด', 6900.00, 14, '/images/figma/prod_nanoflare_1000z.png'),
(19, 2, 'Yonex Arcsaber 11 Pro Racket', 'Yonex', 'ไม้แบดมินตันสายคอนโทรล จับลูกนาน วางลูกแม่นยำดั่งใจสั่ง', 6600.00, 12, '/images/figma/prod_astrox_vertical.png'),
(20, 2, 'Victor Thruster Ryuga II Racket', 'Victor', 'ไม้ประจำตัว ลีซีเจีย พลังตบดุดัน ก้านสปริงตัวดีเยี่ยม', 6150.00, 10, '/images/figma/prod_victor_ryuga.png'),
(21, 2, 'Victor DriveX 10 Metallic Racket', 'Victor', 'ไม้สายรอบด้าน เฟรมเสริม Metallic เพิ่มความแน่นในการวางลูก', 5900.00, 10, '/images/figma/prod_victor_drivex.png'),
(22, 2, 'Li-Ning Axforce 90 Max Racket', 'Li-Ning', 'ไม้แบดตระกูลพยัคฆ์ ก้านสลิม 6.2mm ตบหนักและสวิงรวดเร็ว', 6590.00, 8, '/images/figma/prod_lining_axforce.png'),
(23, 2, 'Yonex Aerosensa 50 Shuttlecocks (12 pcs)', 'Yonex', 'ลูกขนไก่เกรดแข่งขันโอลิมปิก ขนห่านแท้ วิถีลูกนิ่งและทนทานสูงสุด', 1150.00, 50, '/images/figma/prod_aerosensa_50.png'),
(24, 2, 'Victor Master No.1 Shuttlecocks (12 pcs)', 'Victor', 'ลูกขนไก่เกรดแข่งขัน BWF อนุมัติ วิถีแม่นยำ หัวคอร์กเกรดพรีเมียม', 980.00, 45, '/images/figma/prod_victor_shuttle.png'),
(25, 2, 'Yonex Power Cushion 65 Z3 Shoes', 'Yonex', 'รองเท้าแบดมินตันที่นักกีฬาระดับโลกเลือกใช้ ซับแรงกระแทกและเปลี่ยนทิศทางไว', 4950.00, 20, '/images/figma/prod_yonex_65z3_shoes.png'),
(26, 2, 'Victor A970 NitroLite Shoes', 'Victor', 'โฟม NitroLite น้ำหนักเบา กระชับข้อเท้า ซัพพอร์ตการก้าวกระโดด', 4600.00, 18, '/images/figma/prod_victor_a970_shoes.png'),
(27, 2, 'Yonex Pro Tournament Bag (9 Rackets)', 'Yonex', 'กระเป๋าแบดมินตัน 3 ซิป ช่องบุฟอยล์กันความร้อน ช่องใส่รองเท้าแยก', 3450.00, 15, '/images/figma/prod_yonex_bag.png'),
(28, 2, 'Yonex BG66 Ultimax String', 'Yonex', 'เอ็นแบดมินตันเส้นผ่านศูนย์กลาง 0.65mm เสียงใส สปริงลูกดีเยี่ยม', 790.00, 60, '/images/figma/prod_bg66_string.png'),
(29, 2, 'Victor VBS-66 Nano String', 'Victor', 'เอ็นแบดเคลือบ Nano เทคโนโลยี ให้สัมผัสการคอนโทรลลูกยอดเยี่ยม', 250.00, 70, '/images/figma/prod_bg66_string.png'),
(30, 2, 'Yonex Super Grap Grip Tape', 'Yonex', 'กริปพันด้ามยอดนิยมอันดับ 1 ดูดซับเหงื่อ หนึบมือ ไม่ลื่น', 1250.00, 35, '/images/figma/prod_super_grap.png'),
(31, 2, 'Yonex Competition Badminton Net', 'Yonex', 'ตาข่ายแบดมินตันมาตรฐานสากล ขอบไนลอนหนา ทนทานไม่หย่อน', 1890.00, 12, '/images/figma/prod_yonex_bag.png'),
(32, 2, 'Li-Ning Sports Wristband (Pair)', 'Li-Ning', 'ผ้ารัดข้อมือซับเหงื่อ นุ่ม กระชับ ไม่ระคายเคืองผิว', 190.00, 80, '/images/figma/prod_super_grap.png'),
(33, 3, 'Spalding TF-1000 Legacy Basketball', 'Spalding', 'ลูกบาสเกตบอลหนัง PU ไมโครไฟเบอร์ จับกระชับมือ มาตรฐาน FIBA', 2850.00, 20, '/images/figma/prod_spalding_ball.png'),
(34, 3, 'Molten BG4500 FIBA Basketball (Size 7)', 'Molten', 'ลูกบาสเกตบอล 12 แผ่น ผิวสัมผัสแบบพรีเมียม ใช้แข่งระดับชาติ', 2450.00, 30, '/images/figma/prod_spalding_ball.png'),
(35, 3, 'Wilson Evolution Indoor Basketball', 'Wilson', 'ลูกบาสในร่มยอดนิยมอันดับ 1 ในอเมริกา สัมผัสนุ่ม คุมลูกง่าย', 3200.00, 18, '/images/figma/prod_spalding_ball.png'),
(36, 3, 'Nike LeBron 21 Basketball Shoes', 'Nike', 'รองเท้าบาสสายพลัง ซูมแอร์สองชั้น ซัพพอร์ตข้อเท้าและแรงกระแทกสูงสุด', 7200.00, 12, '/images/figma/prod_pegasus_40.png'),
(37, 3, 'Nike KD 16 Basketball Shoes', 'Nike', 'โฟม Cushlon ผสาน Zoom Air ด้านหน้า เคลื่อนไหวคล่องตัวทุกลีลา', 5800.00, 15, '/images/figma/prod_pegasus_40.png'),
(38, 3, 'Under Armour Curry 11', 'Under Armour', 'พื้น UA Flow เบา ไร้ยาง ยึดเกาะพื้นคอร์ทขั้นสุดเพื่อการยิง 3 คะแนน', 6290.00, 14, '/images/figma/prod_spalding_ball.png'),
(39, 3, 'Nike Giannis Freak 5', 'Nike', 'ออกแบบเพื่อการ Euro-step และการตัดเข้าหาห่วงอย่างรวดเร็ว', 4500.00, 16, '/images/figma/prod_spalding_ball.png'),
(40, 3, 'Jordan Luka 2', 'Jordan', 'ซัพพอร์ตการสเต็ปแบ็ค การทรงตัวแน่นหนาด้วยโครงสร้าง IsoPlate', 4700.00, 15, '/images/figma/prod_spalding_ball.png'),
(41, 3, 'Wilson Dual Action Ball Pump', 'Wilson', 'ที่สูบลมแบบดันและดึง มีเกจ์วัดแรงดันลมในตัว พกพาสะดวก', 450.00, 40, '/images/figma/prod_spalding_ball.png'),
(42, 3, 'Spalding Heavy Duty Basketball Net', 'Spalding', 'ตาข่ายแป้นบาสเส้นหนาพิเศษ ทนแดด ทนฝน ไม่ขาดง่าย', 390.00, 50, '/images/figma/prod_spalding_ball.png'),
(43, 3, 'Nike Elite Crew Basketball Socks (3-Pack)', 'Nike', 'ถุงเท้าบาสเกตบอลกันกระแทกเฉพาะจุด ระบายอากาศดีเยี่ยม', 950.00, 45, '/images/figma/prod_spalding_ball.png'),
(44, 3, 'McDavid Hex Knee Pads (Pair)', 'McDavid', 'สนับเข่าโฟมรังผึ้ง HexPad 9mm ป้องกันการกระแทกพื้นคอร์ท', 1690.00, 25, '/images/figma/prod_spalding_ball.png'),
(45, 3, 'SKLZ D-Man Basketball Dummy Defender', 'SKLZ', 'หุ่นจำลองกองหลัง ปรับความสูงได้ สำหรับฝึกยิงและเลี้ยงหลบ', 2990.00, 8, '/images/figma/prod_spalding_ball.png'),
(46, 3, 'Wilson Basketball Backpack with Ball Pocket', 'Wilson', 'กระเป๋าเป้บาสเกตบอล มีตาข่ายใส่ลูกบาสด้านหน้าและช่องใส่รองเท้า', 2190.00, 20, '/images/figma/prod_yonex_bag.png'),
(47, 3, 'Shock Doctor Gel Max Mouthguard', 'Shock Doctor', 'ฟันยางป้องกันแรงกระแทก ปรับรูปทรงตามฟันด้วยน้ำร้อน', 590.00, 35, '/images/figma/prod_spalding_ball.png'),
(48, 3, 'Nike Pro Dri-FIT 3/4 Tights', 'Nike', 'กางเกงรัดรูปบาสเกตบอล 3/4 ขา กระชับกล้ามเนื้อ ลดการบาดเจ็บ', 1400.00, 30, '/images/figma/prod_spalding_ball.png'),
(49, 4, 'Adidas Predator Accuracy Pro FG Boots', 'Adidas', 'รองเท้าสตั๊ดปุ่ม High Definition Grip ยิงลูกไซด์โค้งแม่นยำดั่งใจ', 7200.00, 12, '/images/figma/prod_adidas_conext24.png'),
(50, 4, 'Nike Mercurial Superfly 9 Elite FG', 'Nike', 'รองเท้าสตั๊ดหุ้มข้อ แอร์ซูม 3/4 ตัวท็อปเพื่อนักเตะสายความเร็วระดับโลก', 9400.00, 8, '/images/figma/prod_adidas_conext24.png'),
(51, 4, 'Puma Future 7 Ultimate FG', 'Puma', 'อัปเปอร์ FUZIONFIT360 ปรับกระชับรูปเท้า ยืดหยุ่นสูงสุด', 6800.00, 14, '/images/figma/prod_adidas_conext24.png'),
(52, 4, 'Mizuno Morelia Neo IV Japan FG', 'Mizuno', 'สตั๊ดหนังจิงโจ้แท้ทำด้วยมือในญี่ปุ่น นุ่ม เบา สัมผัสบอลเนียนตา', 8900.00, 10, '/images/figma/prod_adidas_conext24.png'),
(53, 4, 'Adidas Conext 24 Pro Match Ball', 'Adidas', 'ลูกฟุตบอลแข่งขันอย่างเป็นทางการ ไร้รอยต่อ มาตรฐาน FIFA Quality Pro', 4500.00, 20, '/images/figma/prod_adidas_conext24.png'),
(54, 4, 'Nike Flight Official Match Ball', 'Nike', 'ร่อง Aerowsculpt ควบคุมวิถีลูกบอลให้นิ่ง แม่นยำขึ้น 30%', 4900.00, 15, '/images/figma/prod_adidas_conext24.png'),
(55, 4, 'Molten Vantaggio 5000 Football', 'Molten', 'ลูกฟุตบอลหนัง ACENTEC ทนน้ำ ไม่ซึมน้ำ วิถีลูกแน่นอน', 2900.00, 25, '/images/figma/prod_adidas_conext24.png'),
(56, 4, 'Adidas Predator Pro Goalkeeper Gloves', 'Adidas', 'ถุงมือผู้รักษาประตู โฟม URG 2.0 หนึบจับติด โซนชกบอล High Definition', 4200.00, 15, '/images/figma/prod_adidas_conext24.png'),
(57, 4, 'Nike Gripknit Phantom GX Elite FG', 'Nike', 'เส้นใย Gripknit สัมผัสบอลแม่นยำทั้งสนามเปียกและแห้ง', 8500.00, 10, '/images/figma/prod_super_grap.png'),
(58, 4, 'Nike Mercurial Lite Shin Guards', 'Nike', 'สนับแข้งน้ำหนักเบา โครงบางกระชับ พร้อมปลอกรัดระบายเหงื่อ', 850.00, 50, '/images/figma/prod_adidas_conext24.png'),
(59, 4, 'Adidas Tiro League Gym Sack', 'Adidas', 'ถุงผ้าหูรูดใส่รองเท้าสตั๊ดและเสื้อผ้าฟุตบอล น้ำหนักเบา แห้งไว', 550.00, 60, '/images/figma/prod_adidas_conext24.png'),
(60, 4, 'Kwik Goal Captain Armband', 'Kwik Goal', 'ปลอกแขนกัปตันทีม ยางยืดแถบตีนตุ๊กแก กระชับ ไม่เลื่อนหลุด', 290.00, 40, '/images/figma/prod_adidas_conext24.png'),
(61, 4, 'Puma Ultra Grip 1 RC Gloves', 'Puma', 'โฟมลาเท็กซ์ Ultimate Grip 4mm เซฟนิ้ว ป้องกันการบาดเจ็บ', 2890.00, 16, '/images/figma/prod_super_grap.png'),
(62, 4, 'Trusox 3.0 Mid-Calf Cushion Socks', 'Trusox', 'ถุงเท้ากันลื่นระดับพรีเมียม ล็อกเท้ากับสตั๊ดไม่ให้ลื่นไถล', 1450.00, 35, '/images/figma/prod_adidas_conext24.png'),
(63, 4, 'Fox 40 Classic Referee Whistle', 'Fox 40', 'นกหวีดไร้ลูกกลิ้ง เสียงดังคมชัด 115 เดซิเบล ใช้ในฟุตบอลโลก', 350.00, 50, '/images/figma/prod_adidas_conext24.png'),
(64, 4, 'Agility Training Cones (Set of 20)', 'SPORT.CPE', 'กรวยฝึกซ้อมฟุตบอลพลาสติกเหนียว เหยียบไม่แตก พร้อมที่เก็บ', 490.00, 30, '/images/figma/prod_adidas_conext24.png'),
(65, 5, 'Speedo Fastskin Speedsocket 2', 'Speedo', 'แว่นตาว่ายน้ำเลนส์ปรอทตัดแสง ระดับโอลิมปิก ลู่ลมในน้ำ', 2490.00, 18, '/images/figma/prod_speedo_goggles.png'),
(66, 5, 'Arena Cobra Ultra Swipe Mirror Goggles', 'Arena', 'เทคโนโลยี Swipe Anti-Fog ปาดเลนส์แล้วสารกันฝ้ากลับมาทำงาน 10 เท่า', 2790.00, 18, '/images/figma/prod_speedo_goggles.png'),
(67, 5, 'Speedo Aquapulse Pro Mirror Goggles', 'Speedo', 'ซีล IQfit 3D แนบสนิท ไม่กดรอยรอบดวงตา เหมาะกับซ้อมและแข่ง', 1890.00, 25, '/images/figma/prod_speedo_goggles.png'),
(68, 5, 'View Blade Zero Racing Goggles', 'View', 'แว่นตาว่ายน้ำไร้ยางรองตา ดีไซน์แบนราบลดแรงต้านน้ำสูงสุด', 1450.00, 30, '/images/figma/prod_speedo_goggles.png'),
(69, 5, 'Arena Silicone Racing Swim Cap', 'Arena', 'หมวกว่ายน้ำซิลิโคน 100% กระชับเข้ารูปศีรษะ ป้องกันคลอรีน', 450.00, 40, '/images/figma/prod_speedo_goggles.png'),
(70, 5, 'Speedo Fastskin Silicone Cap', 'Speedo', 'หมวกว่ายน้ำ 3D Molded ลดแรงเสียดทานของศีรษะในน้ำ 5.7%', 890.00, 30, '/images/figma/prod_speedo_goggles.png'),
(71, 5, 'Speedo Endurance+ Jammer (Men)', 'Speedo', 'กางเกงว่ายน้ำขาสั้นผ้า Endurance+ ทนคลอรีน 100% ไม่ย้วย แห้งไว', 1690.00, 24, '/images/figma/prod_speedo_goggles.png'),
(72, 5, 'Arena Powerskin Carbon Core FX (Women)', 'Arena', 'ชุดว่ายน้ำแข่งขันระดับโลก โครงสร้างคาร์บอนเคจ รัดกล้ามเนื้อทรงพลัง', 15900.00, 6, '/images/figma/prod_speedo_goggles.png'),
(73, 5, 'Finis Stability Snorkel Speed', 'Finis', 'ท่อหายใจหน้าสำหรับฝึกซ้อมว่ายน้ำ ไร้สายรัดศีรษะ นิ่งแม้กลับตัว', 1850.00, 15, '/images/figma/prod_speedo_goggles.png'),
(74, 5, 'Arena Powerfin Pro Training Fins', 'Arena', 'ตีนกบใบสั้นซิลิโคนแท้ ฝึกเตะขาเร็วและเพิ่มพลังกล้ามเนื้อขา', 2390.00, 20, '/images/figma/prod_speedo_goggles.png'),
(75, 5, 'Speedo Pullbuoy Kickboard Combo', 'Speedo', 'โฟม 2-in-1 เป็นทั้งโฟมเตะขาและโฟมหนีบฝึกแขนในชิ้นเดียว', 990.00, 35, '/images/figma/prod_speedo_goggles.png'),
(76, 5, 'MP Michael Phelps Focus Swim Snorkel', 'MP', 'สนอร์เกิลดีไซน์สามเหลี่ยมลู่ลม ยางกัดซิลิโคนนุ่มสบาย', 1750.00, 16, '/images/figma/prod_speedo_goggles.png'),
(77, 5, 'Arena Fastpack 3.0 Backpack (40L)', 'Arena', 'กระเป๋าเป้นักว่ายน้ำ แขวนตู้ล็อกเกอร์ได้ แยกช่องเปียกและแห้งชัดเจน', 3200.00, 14, '/images/figma/prod_yonex_bag.png'),
(78, 5, 'Speedo Biofuse Finger Paddles', 'Speedo', 'พายมือขนาดนิ้วมือ ฝึกองศาการจับน้ำและลดแรงกระทำต่อหัวไหล่', 690.00, 30, '/images/figma/prod_speedo_goggles.png'),
(79, 5, 'TYR Dry Off Microfiber Sport Towel', 'TYR', 'ผ้าชามัวร์ซับน้ำแห้งไว บิดน้ำออกแล้วใช้ซ้ำได้ทันที พกพาง่าย', 590.00, 40, '/images/figma/prod_speedo_goggles.png'),
(80, 5, 'Speedo Ergo Ear Plugs & Nose Clip Set', 'Speedo', 'ชุดอุดหูและที่หนีบจมูกซิลิโคนกันน้ำเข้า กระชับ นุ่ม ไม่เจ็บ', 380.00, 60, '/images/figma/prod_speedo_goggles.png'),
(81, 6, 'Bowflex SelectTech 552 Adjustable Dumbbells', 'Bowflex', 'ดัมเบลปรับน้ำหนักอัจฉริยะ 2-24 กิโลกรัม เพียงหมุนแป้น', 12900.00, 6, '/images/figma/studio_fitness_dumbbell.svg'),
(82, 6, 'Rogue Fitness The Ohio Barbell (20kg)', 'Rogue', 'แกนบาร์เบลมาตรฐานโอลิมปิก เหล็กกล้า 190,000 PSI ทนทานตลอดชีพ', 14500.00, 8, '/images/figma/studio_fitness_dumbbell.svg'),
(83, 6, 'Rogue Echo Bumper Plates (100kg Set)', 'Rogue', 'แผ่นน้ำหนักยางพรีเมียม ไร้กลิ่น ทนการทิ้งดรอปลงพื้น', 18900.00, 5, '/images/figma/studio_fitness_dumbbell.svg'),
(84, 6, 'Manduka PRO Yoga Mat (6mm)', 'Manduka', 'เสื่อโยคะเกรดมืออาชีพ ซัพพอร์ตข้อต่อสูงสุด ไม่ยุบตัว รับประกันตลอดชีพ', 4980.00, 20, '/images/figma/studio_fitness_dumbbell.svg'),
(85, 6, 'Lululemon The Mat (5mm Reversible)', 'Lululemon', 'เสื่อโยคะยางธรรมชาติ ดูดซับเหงื่อ หนึบแน่นไม่ลื่นแม้เหงื่อออกมาก', 3450.00, 25, '/images/figma/studio_fitness_dumbbell.svg'),
(86, 6, 'Harbinger 4-Inch Padded Leather Weight Belt', 'Harbinger', 'เข็มขัดยกน้ำหนักหนังแท้ บุก้นเบาะนุ่ม ซัพพอร์ตหลังล่างเวลายกหนัก', 1850.00, 22, '/images/figma/studio_fitness_dumbbell.svg'),
(87, 6, 'Gymreapers Heavy Duty Lifting Straps', 'Gymreapers', 'สายรัดข้อมือช่วยยกเวท คอตตอนหนาพิเศษ แผ่นนีโอพรีนซับข้อมือ', 650.00, 50, '/images/figma/studio_fitness_dumbbell.svg'),
(88, 6, 'Theragun Prime Percussive Massage Gun', 'Therabody', 'ปืนนวดกล้ามเนื้อระบบลึก ปรับความเร็วผ่านแอป คลายพังผืดรวดเร็ว', 11900.00, 10, '/images/figma/studio_fitness_dumbbell.svg'),
(89, 6, 'RPM Session 4 Speed Jump Rope', 'RPM', 'เชือกกระโดดสายเคเบิลความเร็วสูง ลูกปืนคู่ หมุนลื่นไหลระดับแข่งขัน', 2450.00, 25, '/images/figma/studio_fitness_dumbbell.svg'),
(90, 6, 'Iron Gym Total Upper Body Workout Bar', 'Iron Gym', 'บาร์โหนติดประตู ไม่ต้องเจาะผนัง ดึงข้อ วิดพื้น ดิพส์ ในตัวเดียว', 1290.00, 30, '/images/figma/studio_fitness_dumbbell.svg'),
(91, 6, 'Power Systems Resistance Bands Set (5 Levels)', 'Power Systems', 'ชุดยางยืดออกกำลังกาย 5 ระดับแรงต้าน พร้อมที่จับและสมอประตู', 990.00, 40, '/images/figma/studio_fitness_dumbbell.svg'),
(92, 6, 'Domyos Ab Wheel with Mat', 'Decathlon', 'ลูกกลิ้งบริหารหน้าท้อง ล้อคู่มั่นคง พร้อมแผ่นรองหัวเข่า', 490.00, 50, '/images/figma/studio_fitness_dumbbell.svg'),
(93, 6, 'Yes4All Adjustable Cast Iron Kettlebell', 'Yes4All', 'เคตเทิลเบลเหล็กหล่อปรับน้ำหนักได้ 4.5 - 18 กิโลกรัม ประหยัดพื้นที่', 3200.00, 15, '/images/figma/studio_fitness_dumbbell.svg'),
(94, 6, 'SBD Weightlifting Knee Sleeves (7mm)', 'SBD', 'ปลอกรัดหัวเข่ามาตรฐานพาวเวอร์ลิฟติ้งโลก ให้ความอบอุ่นและซัพพอร์ตแน่น', 3890.00, 18, '/images/figma/studio_fitness_dumbbell.svg'),
(95, 6, 'BlenderBottle Pro Series Shaker (28oz)', 'BlenderBottle', 'แก้วเชคเวย์โปรตีน สแตนเลสสตีลบอล ชงเนียน ไม่จับตัวเป็นก้อน', 490.00, 60, '/images/figma/studio_fitness_dumbbell.svg'),
(96, 6, 'TriggerPoint GRID Foam Roller (13-inch)', 'TriggerPoint', 'โฟมโรลเลอร์ลวดลายกริด เลียนแบบมือนักกายภาพ คลายกล้ามเนื้อตึง', 1650.00, 30, '/images/figma/studio_fitness_dumbbell.svg'),
(97, 7, 'Quechua 2-Seconds Easy Tent (3-Person)', 'Quechua', 'เต็นท์กางอัตโนมัติใน 2 วินาที กันฝน กันลม พับเก็บง่ายด้วยระบบปุ่มกด', 4990.00, 10, '/images/figma/studio_camping_tent.svg'),
(98, 7, 'Coleman Tough Screen 2-Room House Tent', 'Coleman', 'เต็นท์ครอบครัว 2 ห้องนอน 1 ห้องนั่งเล่น มุ้งกันยุงรอบทิศทาง กันฝน 2,000mm', 19900.00, 4, '/images/figma/studio_camping_tent.svg'),
(99, 7, 'Naturehike Cloud Up 2 Ultralight Tent', 'Naturehike', 'เต็นท์เดินป่าน้ำหนักเบาเพียง 1.4kg ผ้าไนลอน 20D ซิลิโคนกันน้ำ', 3890.00, 15, '/images/figma/studio_camping_tent.svg'),
(100, 7, 'Naturehike Goose Down Sleeping Bag (-5C)', 'Naturehike', 'ถุงนอนขนเป็ดแท้ อบอุ่น นุ่ม เบา ทนอุณหภูมิติดลบ พับเก็บขนาดเล็ก', 3200.00, 14, '/images/figma/prod_yonex_bag.png'),
(101, 7, 'Coleman Quad Chair with Cooler', 'Coleman', 'เก้าอี้แคมป์ปิ้งพับได้ มีช่องเก็บความเย็นใส่เครื่องดื่ม 4 กระป๋องที่เท้าแขน', 1450.00, 25, '/images/figma/studio_camping_tent.svg'),
(102, 7, 'Helinox Chair One Ultralight Camp Chair', 'Helinox', 'เก้าอี้พับโครงอะลูมิเนียม DAC น้ำหนักเพียง 960 กรัม รับน้ำหนักได้ 145kg', 4200.00, 12, '/images/figma/studio_camping_tent.svg'),
(103, 7, 'Snow Peak Titanium Trek 700 Cookset', 'Snow Peak', 'หม้อต้มไทเทเนียมแท้จากญี่ปุ่น เบา ทนทาน ไร้สนิม มีหูจับพับได้', 2350.00, 18, '/images/figma/studio_camping_tent.svg'),
(104, 7, 'Primus Express Backpacking Camp Stove', 'Primus', 'เตาแก๊สหัวพกพา 82 กรัม ไฟแรง 2,600W ต้านลมดีเยี่ยม', 1790.00, 20, '/images/figma/prod_yonex_bag.png'),
(105, 7, 'Stanley Classic Vacuum Bottle (1.0L)', 'Stanley', 'กระติกเก็บความร้อน-เย็นสุญญากาศ สแตนเลสสตีลหนา เก็บความเย็น 24 ชั่วโมง', 1850.00, 30, '/images/figma/studio_camping_tent.svg'),
(106, 7, 'Black Diamond Spot 400 Headlamp', 'Black Diamond', 'ไฟฉายคาดหัวความสว่าง 400 ลูเมน กันน้ำ IPX8 มีไฟสีแดงสำหรับมองกลางคืน', 1950.00, 25, '/images/figma/studio_camping_tent.svg'),
(107, 7, 'Osprey Atmos AG 65 Hiking Backpack', 'Osprey', 'เป้เดินป่าระบบหลังตาข่าย Anti-Gravity ถ่ายเทน้ำหนักลงสะโพกยอดเยี่ยม', 11500.00, 8, '/images/figma/prod_yonex_bag.png'),
(108, 7, 'Gregory Baltoro 75 Trekking Pack', 'Gregory', 'เป้เดินทางระยะไกลระบบ Response A3 ซัพพอร์ตการแบกน้ำหนักหนักหน่วง', 12900.00, 6, '/images/figma/studio_camping_tent.svg'),
(109, 7, 'Leki Makalu FX Carbon Trekking Poles', 'Leki', 'ไม้เท้าเดินป่าคาร์บอนไฟเบอร์พับได้ ด้ามจับ Aergon Air นุ่มกระชับมือ', 6800.00, 14, '/images/figma/studio_camping_tent.svg'),
(110, 7, 'Sawyer Mini Water Filtration System', 'Sawyer', 'เครื่องกรองน้ำพกพา กรองแบคทีเรียและโปรโตซัว 99.9999% กรองได้ 100,000 แกลลอน', 1250.00, 35, '/images/figma/studio_camping_tent.svg'),
(111, 7, 'Therm-a-Rest NeoAir Xlite Sleeping Pad', 'Therm-a-Rest', 'แผ่นรองนอนเป่าลมอัลตร้าไลท์ ค่า R-Value 4.5 กันความหนาวจากพื้นดิน', 7900.00, 10, '/images/figma/studio_camping_tent.svg'),
(112, 7, 'Victorinox Swiss Army Climber Knife', 'Victorinox', 'มีดพับอเนกประสงค์สวิสแท้ 14 ฟังก์ชั่น มีด กรรไกร ที่เปิดกระป๋อง', 1650.00, 40, '/images/figma/studio_camping_tent.svg'),
(113, 8, 'Molten V5M5000 Competition Volleyball', 'Molten', 'ลูกวอลเลย์บอลหนัง PU ผิวสัมผัสรังผึ้ง FLISTATEC ใช้แข่งระดับนานาชาติ', 2100.00, 25, '/images/figma/studio_volleyball.svg'),
(114, 8, 'Mikasa V200W Official Game Ball (FIVB)', 'Mikasa', 'ลูกวอลเลย์บอล 18 แผ่นอย่างเป็นทางการของโอลิมปิกและชิงแชมป์โลก', 2350.00, 20, '/images/figma/studio_volleyball.svg'),
(115, 8, 'Mikasa V300W Match Volleyball', 'Mikasa', 'ลูกวอลเลย์บอลหนังไมโครไฟเบอร์ นุ่มมือ ซับแรงกระแทก เหมาะสำหรับฝึกซ้อมและแข่ง', 1750.00, 30, '/images/figma/studio_volleyball.svg'),
(116, 8, 'Asics Sky Elite FF 2 Volleyball Shoes', 'Asics', 'รองเท้าเพื่อนักตบหัวเสา เทคโนโลยี TWISTRUSS เพิ่มแรงดีดกระโดดตบ', 5500.00, 16, '/images/figma/prod_pegasus_40.png'),
(117, 8, 'Mizuno Wave Lightning Z7 Shoes', 'Mizuno', 'รองเท้าวอลเลย์บอลน้ำหนักเบา คล่องตัว ตอบสนองการพุ่งรับลูกรวดเร็ว', 4700.00, 18, '/images/figma/prod_pegasus_40.png'),
(118, 8, 'Asics Upcourt 5 Volleyball Shoes', 'Asics', 'รองเท้าคอร์ทในร่มระดับเริ่มต้น ยึดเกาะพื้นคอร์ทแน่น ทนทาน', 2200.00, 28, '/images/figma/prod_pegasus_40.png'),
(119, 8, 'Mizuno VS1 Volleyball Knee Pads (Pair)', 'Mizuno', 'สนับเข่าวอลเลย์บอลโฟม VS1 ดูดซับแรงกระแทก ป้องกันการพุ่งขุดลูก', 890.00, 45, '/images/figma/studio_volleyball.svg'),
(120, 8, 'Asics Gel Volleyball Knee Pads', 'Asics', 'สนับเข่าเสริมเจล GEL cushioning นุ่ม ซัพพอร์ตหัวเข่ารอบด้าน', 950.00, 40, '/images/figma/studio_volleyball.svg'),
(121, 8, 'Molten Heavy Duty Volleyball Net', 'Molten', 'ตาข่ายวอลเลย์บอลขึงสลิงเหล็ก ขอบผ้าใบหนา มาตรฐานการแข่งขัน', 2800.00, 12, '/images/figma/studio_volleyball.svg'),
(122, 8, 'Mikasa Beach Classic Volleyball (VLS300)', 'Mikasa', 'ลูกวอลเลย์บอลชายหาดโอลิมปิก กันน้ำ 100% สัมผัสนุ่มบนหาดทราย', 2190.00, 15, '/images/figma/studio_volleyball.svg'),
(123, 8, 'Mikasa Ball Carry Bag (Holds 6 Balls)', 'Mikasa', 'กระเป๋าใส่ลูกบอล 6 ลูก มีสายสะพายไหล่ พกพาไปสนามซ้อมสะดวก', 1190.00, 20, '/images/figma/prod_yonex_bag.png'),
(124, 8, 'Molten Dual Action Ball Hand Pump', 'Molten', 'ที่สูบลมลูกบอล มีสายต่อยืดหยุ่น ป้องกันเข็มสูบหักคาจุกบอล', 420.00, 50, '/images/figma/studio_volleyball.svg'),
(125, 8, 'McDavid Volleyball Arm Compression Sleeves', 'McDavid', 'ปลอกแขนซัพพอร์ตการอันเดอร์บอล ลดแรงกระแทกและรอยช้ำที่แขน', 1150.00, 30, '/images/figma/studio_volleyball.svg'),
(126, 8, 'Mizuno Volleyball Ankle Support Brace', 'Mizuno', 'การ์ดล็อกข้อเท้า ป้องกันข้อเท้าพลิกจากการกระโดดบล็อกและลงสู่พื้น', 990.00, 25, '/images/figma/studio_volleyball.svg'),
(127, 8, 'Tachikara Elastic Volleyball Antenna Set', 'Tachikara', 'เสาอากาศติดตาข่ายวอลเลย์บอล แถบขาว-แดง มองเห็นชัดเจน', 1690.00, 14, '/images/figma/studio_volleyball.svg'),
(128, 8, 'Tandem Sport Volleyball Spike Trainer', 'Tandem Sport', 'อุปกรณ์ฝึกซ้อมการตบลูก แขวนลูกบอลปรับระดับได้ ซ้อมตบได้คนเดียว', 1890.00, 10, '/images/figma/studio_volleyball.svg');

-- 5. Insert Promotion-Products Mapping (M:N Relationship)
INSERT INTO promotion_products (promo_id, product_id) VALUES
(1, 1),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 11),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 23),
(1, 81),
(1, 97),
(1, 100),
(2, 1),
(2, 5),
(2, 17),
(2, 23),
(2, 113),
(2, 116),
(3, 1),
(3, 17),
(3, 18),
(3, 19),
(3, 20),
(3, 21),
(3, 22),
(3, 23),
(3, 24),
(3, 25),
(3, 26),
(3, 27),
(3, 28),
(3, 29),
(3, 30),
(3, 31),
(3, 32),
(3, 33),
(3, 65);

-- 6. Insert Orders (12 Past Realistic Orders)
INSERT INTO orders (order_id, user_id, promo_id, order_date, total_amount, discount_amount, net_amount, shipping_address, order_status, payment_method) VALUES
(1001, 1, 1, '2026-10-15 10:30:00', 11500.00, 1725.00, 9775.00, 'ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มช. 50200', 'Delivered', 'PromptPay'),
(1002, 3, 2, '2026-10-17 14:15:00', 9500.00, 1900.00, 7600.00, '128/4 ถ.ห้วยแก้ว ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Delivered', 'PromptPay'),
(1003, 4, NULL, '2026-10-18 16:45:00', 3780.00, 0.00, 3780.00, '45/2 หมู่ 5 ถ.นิมมานเหมินท์ ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Delivered', 'PromptPay'),
(1004, 5, 3, '2026-10-20 11:20:00', 4900.00, 2450.00, 2450.00, 'หอพักนักศึกษาชาย อาคาร 4 มหาวิทยาลัยเชียงใหม่ 50200', 'Shipped', 'PromptPay'),
(1005, 6, 1, '2026-10-22 09:10:00', 12900.00, 1935.00, 10965.00, '89 ถ.คลองชลประทาน ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Shipped', 'PromptPay'),
(1006, 1, NULL, '2026-10-24 18:30:00', 4500.00, 0.00, 4500.00, 'ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มช. 50200', 'Shipped', 'PromptPay'),
(1007, 3, 1, '2026-10-26 13:00:00', 8190.00, 1228.50, 6961.50, '128/4 ถ.ห้วยแก้ว ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Paid', 'PromptPay'),
(1008, 4, 2, '2026-10-27 15:40:00', 7850.00, 1570.00, 6280.00, '45/2 หมู่ 5 ถ.นิมมานเหมินท์ ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Paid', 'PromptPay'),
(1009, 5, NULL, '2026-10-28 20:15:00', 4300.00, 0.00, 4300.00, 'หอพักนักศึกษาชาย อาคาร 4 มหาวิทยาลัยเชียงใหม่ 50200', 'Paid', 'PromptPay'),
(1010, 6, NULL, '2026-10-29 11:05:00', 6430.00, 0.00, 6430.00, '89 ถ.คลองชลประทาน ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Pending', 'PromptPay'),
(1011, 1, 1, '2026-10-30 16:50:00', 8900.00, 1335.00, 7565.00, 'ภาควิชาวิศวกรรมคอมพิวเตอร์ คณะวิศวกรรมศาสตร์ มช. 50200', 'Pending', 'PromptPay'),
(1012, 3, NULL, '2026-10-31 08:20:00', 4200.00, 0.00, 4200.00, '128/4 ถ.ห้วยแก้ว ต.สุเทพ อ.เมือง จ.เชียงใหม่ 50200', 'Cancelled', 'PromptPay');

-- 7. Insert Order Items (Line Items with unit_price, item_discount, subtotal)
INSERT INTO order_items (order_id, product_id, quantity, unit_price, item_discount, subtotal) VALUES
(1001, 17, 1, 6900.00, 1035.00, 5865.00),
(1001, 23, 4, 1150.00, 690.00, 3910.00),
(1002, 1, 1, 4600.00, 920.00, 3680.00),
(1002, 5, 1, 4900.00, 980.00, 3920.00),
(1003, 65, 1, 2790.00, 0.00, 2790.00),
(1003, 75, 1, 990.00, 0.00, 990.00),
(1004, 34, 2, 2450.00, 2450.00, 2450.00),
(1005, 81, 1, 12900.00, 1935.00, 10965.00),
(1006, 53, 1, 4500.00, 0.00, 4500.00),
(1007, 97, 1, 4990.00, 748.50, 4241.50),
(1007, 100, 1, 3200.00, 480.00, 2720.00),
(1008, 113, 1, 2350.00, 470.00, 1880.00),
(1008, 116, 1, 5500.00, 1100.00, 4400.00),
(1009, 39, 1, 390.00, 0.00, 390.00),
(1009, 42, 2, 1690.00, 0.00, 3380.00),
(1009, 43, 1, 530.00, 0.00, 530.00),
(1010, 84, 1, 4980.00, 0.00, 4980.00),
(1010, 87, 1, 1450.00, 0.00, 1450.00),
(1011, 11, 1, 8900.00, 1335.00, 7565.00),
(1012, 102, 1, 4200.00, 0.00, 4200.00);

-- Set Featured Products (Products displayed on homepage top banner)
UPDATE products SET is_featured = 1 WHERE product_id IN (1, 17, 18, 25, 33, 49, 65, 81, 97, 113);

SET FOREIGN_KEY_CHECKS = 1;

-- ====================================================================
-- Verification Query (Check All 7 Tables)
-- ====================================================================
SELECT 'categories' AS tbl, COUNT(*) AS count FROM categories
UNION ALL SELECT 'users', COUNT(*) FROM users
UNION ALL SELECT 'promotions', COUNT(*) FROM promotions
UNION ALL SELECT 'products', COUNT(*) FROM products
UNION ALL SELECT 'promotion_products', COUNT(*) FROM promotion_products
UNION ALL SELECT 'orders', COUNT(*) FROM orders
UNION ALL SELECT 'order_items', COUNT(*) FROM order_items;

