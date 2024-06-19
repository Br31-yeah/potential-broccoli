<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $content = $_POST['content'];
  $star = $_POST['star']; // 별점 정보 추가
  
  // 데이터 파일 로드
  $data = json_decode(file_get_contents('data.json'), true) ?? [];
  
  // 데이터 추가
  $newReview = [
    'restaurant' => $_POST['restaurant'],
    'dish' => $_POST['dish'],
    'star' => $star,
    'date' => $_POST['date']
  ];
  
  $data[] = $newReview; // 새 리뷰를 데이터에 추가
  
  // 데이터 저장
  file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT)); // JSON_PRETTY_PRINT를 사용하여 보기 좋게 저장
  
  // 별점에 따라 데이터를 분류하여 파일에 저장
  $hof_data = array_filter($data, fn($review) => $review['star'] == 5 || $review['star'] == 4);
  $anga_data = array_filter($data, fn($review) => $review['star'] <= 3);
  
  file_put_contents('hof_list.html', json_encode(array_values($hof_data), JSON_PRETTY_PRINT)); // array_values를 사용하여 숫자 키를 재정렬
  file_put_contents('anga_list.html', json_encode(array_values($anga_data), JSON_PRETTY_PRINT)); // array_values를 사용하여 숫자 키를 재정렬
  
  // 응답
  http_response_code(200); // 성공적으로 처리되었음을 나타내는 상태 코드
  echo json_encode(['message' => '리뷰가 저장되었습니다.']); // JSON 형식으로 응답
}
?>
